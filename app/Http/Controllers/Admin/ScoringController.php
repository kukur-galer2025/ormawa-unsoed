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
        $aspects = collect();

        if ($division) {
            $applications = $division->applications()
                ->with(['user.mahasiswaProfile', 'scores.criteria'])
                ->latest()
                ->get();

            // Load aspects with criteria and value labels
            $aspects = $division->aspects()
                ->with(['criteria.valueLabels'])
                ->orderBy('urutan')
                ->get();
        }

        return view('admin.scoring.index', compact('recruitment', 'applications', 'aspects', 'division'));
    }

    public function store(Request $request, Recruitment $recruitment, Application $application)
    {
        $this->ensureFullOwnership($recruitment, $application);

        if (!in_array($recruitment->status, ['ditutup', 'selesai'])) {
            return back()->with('error', 'Input nilai hanya bisa dilakukan setelah rekrutmen ditutup.');
        }

        $division = $application->division;
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