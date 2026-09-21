<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Ormawa;
use App\Models\Recruitment;
use Illuminate\Http\Request;

class RecruitmentBrowseController extends Controller
{
    public function index(Request $request)
    {
        $query = Ormawa::where('is_active', true)->withCount('recruitments');

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        if ($request->filled('fakultas')) {
            $query->where('fakultas_id', $request->fakultas);
        }

        if ($request->filled('jurusan')) {
            $query->where('jurusan_id', $request->jurusan);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'LIKE', '%' . $request->search . '%');
        }

        $ormawas = $query->paginate(12)->withQueryString();

        $fakultasList = \App\Models\Fakultas::with('jurusans')->orderBy('nama_fakultas')->get();

        return view('mahasiswa.recruitment.index', compact('ormawas', 'fakultasList'));
    }

    public function show(Ormawa $ormawa)
    {
        if (!$ormawa->is_active) {
            abort(404);
        }

        $ormawa->load(['prestasis' => function($query) {
            $query->orderBy('tahun', 'desc')->latest();
        }, 'programKerjas', 'recruitments' => function($query) {
            $query->where('status', 'dibuka')->where('tanggal_tutup', '>=', now());
        }, 'recruitments.divisions' => function ($query) {
            // Load divisions with application count
            $query->withCount('applications');
        }]);

        // Get user's current applications to check which divisions they already applied to
        $appliedDivisionIds = auth()->user()->applications()->pluck('recruitment_division_id')->toArray();

        return view('mahasiswa.recruitment.show', compact('ormawa', 'appliedDivisionIds'));
    }

    public function prestasi(Ormawa $ormawa)
    {
        if (!$ormawa->is_active) {
            abort(404);
        }

        $ormawa->load(['prestasis' => function($query) {
            $query->orderBy('tahun', 'desc')->latest();
        }]);

        return view('mahasiswa.recruitment.prestasi', compact('ormawa'));
    }

    public function programKerja(Ormawa $ormawa)
    {
        if (!$ormawa->is_active) {
            abort(404);
        }

        $ormawa->load('programKerjas');

        return view('mahasiswa.recruitment.program-kerja', compact('ormawa'));
    }
}