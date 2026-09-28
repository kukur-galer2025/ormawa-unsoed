<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $applications = $user->applications()
            ->with(['recruitment.ormawa'])
            ->latest()
            ->take(5)
            ->get();

        $openRecruitments = Recruitment::reallyOpen()
            ->with('ormawa')
            ->count();

        return view('mahasiswa.dashboard', compact('applications', 'openRecruitments'));
    }
}