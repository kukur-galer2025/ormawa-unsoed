<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\ChecksRecruitmentOwnership;
use App\Models\Application;
use App\Models\ApplicationScore;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use Illuminate\Http\Request;

class ScoringController extends Controller
{
    use ChecksRecruitmentOwnership;
    public function selectRecruitment()
    {
        $ormawa = $this->getAdminOrmawa();
        $recruitments = $ormawa->recruitments()
            ->withCount(['applications', 'divisions'])
            ->latest()
            ->paginate(10);
            
        $pageTitle = "Pilih Rekrutmen (Input Nilai)";
        $pageDescription = "Pilih rekrutmen untuk mulai memberikan nilai (scoring) pada pelamar.";
        $targetRoute = "admin.scoring.index";

        return view('admin.shared.select-recruitment', compact('recruitments', 'pageTitle', 'pageDescription', 'targetRoute'));
    }

    public function index(Recruitment $recruitment)
    {
        $this->ensureRecruitmentOwnership($recruitment);

        if (!in_array($recruitment->status, ['ditutup', 'selesai'])) {
            return redirect()->route('admin.recruitment.show', $recruitment)
                ->with('error', 'Input nilai hanya bisa dilakukan setelah rekrutmen ditutup.');
        }

        $recruitment->load('divisions');
        $divisionId = request('division_id', $recruitment->divisions->first()?->id);
        $division = $recruitment->divisions->find($divisionId);

        $applications = collect();
        $allDivisionApplications = collect();
        $aspects = collect();

        if ($division) {
            $baseQuery = $division->applications()
                ->with(['user.mahasiswaProfile', 'scores.criteria'])
                ->latest();

            // All applications for this division (for the dropdown list)
            $allDivisionApplications = (clone $baseQuery)->get();

            if (request('application_id')) {
                $baseQuery->where('id', request('application_id'));
                $applications = $baseQuery->get();
            } else {
                $applications = collect(); // Force user to select an applicant
            }

            // Load aspects with criteria and value labels
            $aspects = $division->aspects()
                ->with(['criteria.valueLabels'])
                ->orderBy('urutan')
                ->get();
        }

        $divisionsJson = $recruitment->divisions()->with(['applications.user.mahasiswaProfile.jurusanRel'])->get()->map(function($div) {
            return [
                'id' => $div->id,
                'nama' => $div->nama,
                'applications' => $div->applications->map(function($app) {
                    $profile = $app->user->mahasiswaProfile;
                    return [
                        'id' => $app->id,
                        'name' => $app->user->name,
                        'nim' => $profile->nim ?? '-',
                        'jurusan' => $profile->jurusanRel->nama_jurusan ?? '-',
                        'status_text' => $app->status === 'terkirim' ? 'Belum Dinilai' : 'Sudah Dinilai'
                    ];
                })
            ];
        })->toJson();

        return view('admin.scoring.index', compact('recruitment', 'applications', 'allDivisionApplications', 'aspects', 'division', 'divisionsJson'));
    }

    public function store(Request $request, Recruitment $recruitment, Application $application)
    {
        $this->ensureFullOwnership($recruitment, $application);

        if ($recruitment->status !== 'ditutup') {
            return back()->with('error', 'Input nilai hanya bisa dilakukan saat status rekrutmen sedang "ditutup".');
        }

        $division = $application->division;
        if ($division->is_finalized) {
            return back()->with('error', 'Divisi ini sudah difinalisasi. Nilai tidak dapat diubah lagi.');
        }
        $allCriteria = $division->allCriteria()->get();

        $rules = [];
        foreach ($allCriteria as $c) {
            $rules["scores.{$c->id}"] = 'required|integer|min:1|max:5';
        }
        $request->validate($rules);

        foreach ($request->scores as $criteriaId => $actualValue) {
            ApplicationScore::updateOrCreate(
                [
                    'application_id' => $application->id,
                    'criteria_id' => $criteriaId,
                ],
                [
                    'actual_value' => $actualValue,
                ]
            );
        }

        $application->update(['status' => 'diproses']);

        return back()->with('success', 'Nilai pelamar berhasil disimpan.');
    }
}