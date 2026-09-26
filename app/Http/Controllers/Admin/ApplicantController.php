<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\ChecksRecruitmentOwnership;
use App\Models\Application;
use App\Models\Recruitment;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    use ChecksRecruitmentOwnership;

    public function selectRecruitment()
    {
        $ormawa = $this->getAdminOrmawa();
        $recruitments = $ormawa->recruitments()
            ->withCount(['applications', 'divisions'])
            ->latest()
            ->paginate(10);
            
        $pageTitle = "Pilih Rekrutmen (Data Pelamar)";
        $pageDescription = "Pilih rekrutmen untuk melihat daftar pendaftar dan berkas mereka.";
        $targetRoute = "admin.applicants.index";

        return view('admin.shared.select-recruitment', compact('recruitments', 'pageTitle', 'pageDescription', 'targetRoute'));
    }

    public function index(Recruitment $recruitment)
    {
        $this->ensureRecruitmentOwnership($recruitment);

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
        $this->ensureFullOwnership($recruitment, $application);

        $application->load(['user.mahasiswaProfile', 'division', 'scores.criteria', 'profileMatchingResult']);
        return view('admin.applicant.show', compact('recruitment', 'application'));
    }

}