<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $ormawa = $user->ormawas()->first();

        if (!$ormawa) {
            return view('admin.no-ormawa');
        }

        $stats = [
            'total_rekrutmen' => $ormawa->recruitments()->count(),
            'rekrutmen_aktif' => $ormawa->recruitments()->where('status', 'dibuka')->count(),
            'total_pelamar' => $ormawa->recruitments()->withCount('applications')->get()->sum('applications_count'),
        ];

        $recentRecruitments = $ormawa->recruitments()->latest()->take(5)->get();

        return view('admin.dashboard', compact('ormawa', 'stats', 'recentRecruitments'));
    }
}