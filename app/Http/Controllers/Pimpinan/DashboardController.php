<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Ormawa;
use App\Models\Recruitment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalOrmawa = Ormawa::visibleTo($user)->count();
        $totalRekrutmen = Recruitment::visibleTo($user)->count();
        $rekrutmenAktif = Recruitment::visibleTo($user)->reallyOpen()->count();
        $totalProker = \App\Models\OrmawaProgramKerja::whereHas('ormawa', fn($q) => $q->visibleTo($user))->count();
        $totalPrestasi = \App\Models\OrmawaPrestasi::whereHas('ormawa', fn($q) => $q->visibleTo($user))->count();

        $recentRecruitments = Recruitment::visibleTo($user)
            ->with('ormawa')
            ->latest()
            ->take(5)
            ->get();

        $ormawas = Ormawa::visibleTo($user)
            ->where('is_active', true)
            ->withCount(['recruitments', 'programKerjas', 'prestasis'])
            ->latest()
            ->take(6)
            ->get();

        return view('pimpinan.dashboard', compact(
            'user',
            'totalOrmawa',
            'totalRekrutmen',
            'rekrutmenAktif',
            'totalProker',
            'totalPrestasi',
            'recentRecruitments',
            'ormawas',
        ));
    }
}
