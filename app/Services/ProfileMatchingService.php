<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApplicationScore;
use App\Models\Aspect;
use App\Models\ProfileMatchingResult;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use Illuminate\Support\Collection;

class ProfileMatchingService
{
    /**
     * Tabel konversi bobot gap (standar Profile Matching)
     * Gap => Bobot Nilai
     */
    const GAP_MAPPING = [
        0  => 5,
        1  => 4.5,
        -1 => 4,
        2  => 3.5,
        -2 => 3,
        3  => 2.5,
        -3 => 2,
        4  => 1.5,
        -4 => 1,
    ];

    /**
     * Step 1 & 2: Hitung gap dan konversi ke bobot
     */
    public function calculateGapAndWeight(int $actualValue, int $targetValue): array
    {
        $gap = $actualValue - $targetValue;
        
        if (!array_key_exists($gap, self::GAP_MAPPING)) {
            throw new \InvalidArgumentException("Nilai gap {$gap} berada di luar batas mapping yang diizinkan (-4 s/d 4). Periksa kembali nilai aktual dan target.");
        }

        $bobot = self::GAP_MAPPING[$gap];

        return [
            'gap' => $gap,
            'bobot_gap' => $bobot,
        ];
    }

    /**
     * Step 1-2: Update semua gap & bobot untuk satu application
     */
    public function updateScoreWeights(Application $application): void
    {
        $scores = $application->scores()->with('criteria')->get();

        foreach ($scores as $score) {
            if ($score->actual_value !== null) {
                $result = $this->calculateGapAndWeight(
                    $score->actual_value,
                    $score->criteria->target_value
                );
                $score->update([
                    'gap' => $result['gap'],
                    'bobot_gap' => $result['bobot_gap'],
                ]);
            }
        }
    }

    /**
     * Step 3: Hitung NCF dan NSF untuk satu aspek dari satu application
     */
    public function calculateFactorsForAspect(Application $application, Aspect $aspect): array
    {
        $criteriaIds = $aspect->criteria->pluck('id');
        $scores = $application->scores->whereIn('criteria_id', $criteriaIds);

        $coreScores = $scores->filter(fn($s) => $s->criteria->tipe === 'core');
        $secondaryScores = $scores->filter(fn($s) => $s->criteria->tipe === 'secondary');

        $ncf = $coreScores->count() > 0 ? $coreScores->avg('bobot_gap') : 0;
        $nsf = $secondaryScores->count() > 0 ? $secondaryScores->avg('bobot_gap') : 0;

        return [
            'ncf' => round($ncf, 2),
            'nsf' => round($nsf, 2),
        ];
    }

    /**
     * Step 4: Hitung nilai satu aspek
     * Nilai Aspek = (CF% × NCF + SF% × NSF)
     */
    public function calculateAspectScore(float $ncf, float $nsf, float $cfPercent, float $sfPercent): float
    {
        return round(($cfPercent / 100 * $ncf) + ($sfPercent / 100 * $nsf), 2);
    }

    /**
     * Process Profile Matching for a specific DIVISION.
     * 
     * Rumus:
     * 1. Per kriteria: hitung GAP = actual - target, konversi ke bobot
     * 2. Per aspek: NCF = rata-rata bobot CF criteria, NSF = rata-rata bobot SF criteria
     * 3. Nilai aspek = CF% × NCF + SF% × NSF
     * 4. Total = Σ (bobot_aspek × nilai_aspek)
     * 5. Ranking berdasarkan total score desc
     */
    public function processDivision(Recruitment $recruitment, RecruitmentDivision $division): Collection
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($recruitment, $division) {
            $applications = $division->applications()
                ->where('status', '!=', 'ditolak')
                ->with(['scores.criteria'])
                ->get();

            $aspects = $division->aspects()->with('criteria')->orderBy('urutan')->get();

            $results = collect();

        foreach ($applications as $application) {
            // Step 1-2: Update gap & bobot for all scores
            $this->updateScoreWeights($application);
            // Refresh scores after update
            $application->load('scores.criteria');

            $detailPerAspek = [];
            $totalScore = 0;

            foreach ($aspects as $aspect) {
                // Step 3: NCF & NSF per aspek
                $factors = $this->calculateFactorsForAspect($application, $aspect);

                // Step 4: Nilai aspek
                $nilaiAspek = $this->calculateAspectScore(
                    $factors['ncf'],
                    $factors['nsf'],
                    (float) $aspect->cf_percentage,
                    (float) $aspect->sf_percentage
                );

                // Step 5: Kontribusi ke total = bobot × nilai_aspek
                $totalScore += (float) $aspect->bobot * $nilaiAspek;

                $detailPerAspek[] = [
                    'aspect_id' => $aspect->id,
                    'nama' => $aspect->nama,
                    'bobot' => (float) $aspect->bobot,
                    'cf_percentage' => (float) $aspect->cf_percentage,
                    'sf_percentage' => (float) $aspect->sf_percentage,
                    'ncf' => $factors['ncf'],
                    'nsf' => $factors['nsf'],
                    'nilai_aspek' => $nilaiAspek,
                ];
            }

            $results->push([
                'application_id' => $application->id,
                'recruitment_division_id' => $division->id,
                'detail_per_aspek' => $detailPerAspek,
                'total_score' => round($totalScore, 2),
            ]);
        }

        // Step 6: Sort dan assign ranking
        $results = $results->sortByDesc('total_score')->values();

        foreach ($results as $index => $result) {
            ProfileMatchingResult::updateOrCreate(
                [
                    'application_id' => $result['application_id'],
                    'recruitment_division_id' => $result['recruitment_division_id'],
                ],
                [
                    'detail_per_aspek' => $result['detail_per_aspek'],
                    'total_score' => $result['total_score'],
                    'ranking' => $index + 1,
                ]
            );

            // Auto-decision: update status rekomendasi berdasarkan ranking vs kuota
            $ranking = $index + 1;
            $app = Application::find($result['application_id']);
            if ($app) {
                $app->update([
                    'status_rekomendasi' => $ranking <= $division->kuota ? 'direkomendasikan' : 'tidak direkomendasikan'
                ]);
            }
        }

        return $results;
        });
    }
}