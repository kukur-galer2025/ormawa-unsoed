<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Ormawa;
use App\Models\Recruitment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_ormawa' => Ormawa::count(),
            'total_admin' => User::where('role', 'admin')->count(),
            'total_mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'rekrutmen_aktif' => Recruitment::reallyOpen()->count(),
        ];

        $recentRecruitments = Recruitment::with('ormawa')
            ->latest()
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact('stats', 'recentRecruitments'));
    }
}