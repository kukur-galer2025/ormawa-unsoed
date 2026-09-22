<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\ChecksRecruitmentOwnership;
use App\Models\Application;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use App\Services\ProfileMatchingService;
use Illuminate\Http\Request;

class ProfileMatchingController extends Controller
{
    use ChecksRecruitmentOwnership;
    protected ProfileMatchingService $service;

    public function __construct(ProfileMatchingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $ormawa = $this->getAdminOrmawa();
        $recruitments = $ormawa->recruitments()
            ->where('status', '!=', 'draft')
            ->withCount(['divisions', 'applications'])
            ->latest()
            ->paginate(10);

        return view('admin.profile-matching.index', compact('recruitments'));
    }

    public function show(Recruitment $recruitment)
    {
        $this->ensureRecruitmentOwnership($recruitment);

        $recruitment->load(['divisions' => function ($q) {
            $q->withCount(['applications', 'profileMatchingResults']);
        }, 'divisions.aspects.criteria']);

        foreach ($recruitment->divisions as $div) {
            $div->allScored = true;
            $div->hasAspects = $div->aspects->count() > 0;
            $div->bobotValid = false;

            if ($div->hasAspects) {
                // Check total bobot = 1.0
                $totalBobot = $div->aspects->sum('bobot');
                $div->bobotValid = abs($totalBobot - 1.0) < 0.01;

                // Check each aspect has at least one core and one secondary
                $div->aspectsReady = $div->aspects->every(function ($aspect) {
                    return $aspect->criteria->where('tipe', 'core')->count() > 0
                        && $aspect->criteria->where('tipe', 'secondary')->count() > 0;
                });

                // Check all applicants have been scored
                if ($div->applications_count > 0) {
                    $allCriteriaIds = $div->aspects->flatMap(fn($a) => $a->criteria->pluck('id'));
                    foreach ($div->applications()->with('scores')->get() as $app) {
                        foreach ($allCriteriaIds as $cId) {
                            $score = $app->scores->firstWhere('criteria_id', $cId);
                            if (!$score || $score->actual_value === null) {
                                $div->allScored = false;
                                break 2;
                            }
                        }
                    }
                }
            }
        }

        return view('admin.profile-matching.show', compact('recruitment'));
    }

    public function calculate(Recruitment $recruitment, RecruitmentDivision $division)
    {
        $this->ensureRecruitmentOwnership($recruitment);
        $this->ensureDivisionBelongsToRecruitment($recruitment, $division);

        abort_if($recruitment->status === 'dibuka', 403, 'Rekrutmen masih berjalan. Kalkulasi belum diizinkan.');

        if ($division->is_finalized) {
            return back()->with('error', 'Divisi ini sudah difinalisasi. Tidak bisa menghitung ulang.');
        }

        if ($division->applications()->count() === 0) {
            return back()->with('error', 'Tidak ada pelamar di divisi ini.');
        }

        if ($division->aspects->isEmpty()) {
            return back()->with('error', 'Divisi ini belum memiliki aspek penilaian.');
        }

        $totalBobot = $division->aspects->sum('bobot');
        if (abs($totalBobot - 1.0) > 0.01) {
            return back()->with('error', 'Total bobot semua aspek harus = 100% (1.0). Saat ini: ' . round($totalBobot * 100) . '%.');
        }

        try {
            $this->service->processDivision($recruitment, $division);
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', 'Gagal memproses kalkulasi: ' . $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat kalkulasi: ' . $e->getMessage());
        }

        return redirect()->route('admin.profile-matching.result', [$recruitment, $division])
            ->with('success', 'Kalkulasi Profile Matching untuk divisi "' . $division->nama . '" berhasil.');
    }

    public function result(Recruitment $recruitment, RecruitmentDivision $division)
    {
        $this->ensureRecruitmentOwnership($recruitment);
        $this->ensureDivisionBelongsToRecruitment($recruitment, $division);

        $results = $division->profileMatchingResults()
            ->with(['application.user.mahasiswaProfile'])
            ->orderBy('ranking')
            ->get();

        $division->load('aspects.criteria');

        // Load all divisions for the dropdown filter
        $allDivisions = $recruitment->divisions()->withCount('applications')->get();

        return view('admin.profile-matching.result', compact('recruitment', 'division', 'results', 'allDivisions'));
    }

    public function finalize(Request $request, Recruitment $recruitment, RecruitmentDivision $division)
    {
        $this->ensureRecruitmentOwnership($recruitment);
        $this->ensureDivisionBelongsToRecruitment($recruitment, $division);

        if ($division->is_finalized) {
            return back()->with('error', 'Divisi ini sudah difinalisasi sebelumnya.');
        }

        // Harus sudah ada hasil PM
        if ($division->profileMatchingResults()->count() === 0) {
            return back()->with('error', 'Belum ada hasil Profile Matching. Hitung ranking terlebih dahulu.');
        }

        $request->validate([
            'accepted_ids' => 'required|array|min:1',
            'accepted_ids.*' => 'integer|exists:pendaftaran,id',
        ], [
            'accepted_ids.required' => 'Pilih minimal satu pelamar yang diterima.',
            'accepted_ids.min' => 'Pilih minimal satu pelamar yang diterima.',
        ]);

        $acceptedIds = $request->accepted_ids;

        // Validasi: semua ID harus milik divisi ini
        $validCount = $division->applications()
            ->whereIn('id', $acceptedIds)
            ->count();

        if ($validCount !== count($acceptedIds)) {
            return back()->with('error', 'Ada pelamar yang tidak valid untuk divisi ini.');
        }

        // Validasi: jumlah yang dipilih tidak boleh melebihi kuota
        if ($division->kuota > 0 && count($acceptedIds) > $division->kuota) {
            return back()->with('error', 'Jumlah yang dipilih melebihi kuota divisi (' . $division->kuota . ').');
        }

        // Eksekusi dalam transaction
        \DB::transaction(function () use ($division, $acceptedIds) {
            // Yang dipilih → diterima
            Application::where('recruitment_division_id', $division->id)
                ->whereIn('id', $acceptedIds)
                ->update(['status' => 'diterima']);

            // Sisanya → ditolak
            Application::where('recruitment_division_id', $division->id)
                ->whereNotIn('id', $acceptedIds)
                ->update(['status' => 'ditolak']);

            // Lock divisi
            $division->update(['is_finalized' => true]);
        });

        return redirect()->route('admin.profile-matching.result', [$recruitment, $division])
            ->with('success', 'Keputusan untuk divisi "' . $division->nama . '" telah difinalisasi. ' . count($acceptedIds) . ' pelamar diterima.');
    }

    public function announce(Recruitment $recruitment)
    {
        $this->ensureRecruitmentOwnership($recruitment);

        if ($recruitment->is_announced) {
            return back()->with('error', 'Hasil rekrutmen ini sudah diumumkan sebelumnya.');
        }

        // Cek semua divisi sudah difinalisasi
        $totalDivisions = $recruitment->divisions()->count();
        $finalizedDivisions = $recruitment->divisions()->where('is_finalized', true)->count();

        if ($totalDivisions === 0) {
            return back()->with('error', 'Rekrutmen ini belum memiliki divisi.');
        }

        if ($finalizedDivisions < $totalDivisions) {
            $remaining = $totalDivisions - $finalizedDivisions;
            return back()->with('error', "Masih ada {$remaining} divisi yang belum difinalisasi. Selesaikan semua divisi terlebih dahulu.");
        }

        $recruitment->update([
            'is_announced' => true,
            'status' => 'selesai',
        ]);

        return back()->with('success', 'Hasil rekrutmen "' . $recruitment->judul . '" telah diumumkan! Mahasiswa sekarang dapat melihat status kelulusannya.');
    }
}