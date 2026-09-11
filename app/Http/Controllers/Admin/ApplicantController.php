<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Recruitment;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index(Recruitment $recruitment)
    {
        $recruitment->load('divisions');
        $divisionId = request('division_id');

        $query = $recruitment->applications()
            ->with(['user.mahasiswaProfile', 'division', 'profileMatchingResult'])
            ->latest();

        if ($divisionId) {
            $query->where('recruitment_division_id', $divisionId);
        }

        $applications = $query->paginate(15)->withQueryString();
        return view('admin.applicant.index', compact('recruitment', 'applications'));
    }

    public function show(Recruitment $recruitment, Application $application)
    {
        $application->load(['user.mahasiswaProfile', 'division', 'scores.criteria', 'profileMatchingResult']);
        return view('admin.applicant.show', compact('recruitment', 'application'));
    }

}