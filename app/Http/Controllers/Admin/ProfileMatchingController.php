<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use App\Services\ProfileMatchingService;
use Illuminate\Http\Request;

class ProfileMatchingController extends Controller
{
    protected ProfileMatchingService $service;

    public function __construct(ProfileMatchingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $ormawa = auth()->user()->ormawas()->first();
        $recruitments = $ormawa->recruitments()
            ->where('status', '!=', 'draft')
            ->withCount(['divisions', 'applications'])
            ->latest()
            ->paginate(10);

        return view('admin.profile-matching.index', compact('recruitments'));
    }

    public function show(Recruitment $recruitment)
    {
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

        $this->service->processDivision($recruitment, $division);

        return redirect()->route('admin.profile-matching.result', [$recruitment, $division])
            ->with('success', 'Kalkulasi Profile Matching untuk divisi "' . $division->nama . '" berhasil.');
    }

    public function result(Recruitment $recruitment, RecruitmentDivision $division)
    {
        $results = $division->profileMatchingResults()
            ->with(['application.user.mahasiswaProfile'])
            ->orderBy('ranking')
            ->get();

        $division->load('aspects.criteria');

        // Load all divisions for the dropdown filter
        $allDivisions = $recruitment->divisions()->withCount('applications')->get();

        return view('admin.profile-matching.result', compact('recruitment', 'division', 'results', 'allDivisions'));
    }
}