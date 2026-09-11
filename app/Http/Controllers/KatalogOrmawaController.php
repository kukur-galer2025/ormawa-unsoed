<?php

namespace App\Http\Controllers;

use App\Models\Ormawa;
use Illuminate\Http\Request;

class KatalogOrmawaController extends Controller
{
    public function index(Request $request)
    {
        $query = Ormawa::where('is_active', true)->withCount('recruitments');

        // Filter by Tingkat
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        // Filter by Fakultas
        if ($request->filled('fakultas')) {
            $query->where('fakultas', 'LIKE', '%' . $request->fakultas . '%');
        }

        // Filter by Jurusan
        if ($request->filled('jurusan')) {
            $query->where('jurusan', 'LIKE', '%' . $request->jurusan . '%');
        }

        // Search by Nama
        if ($request->filled('search')) {
            $query->where('nama', 'LIKE', '%' . $request->search . '%');
        }

        $ormawas = $query->paginate(12)->withQueryString();

        // Get unique fakultas and jurusan for filter dropdowns (optional, but good for UX)
        $fakultasList = Ormawa::whereNotNull('fakultas')->where('fakultas', '!=', '')->distinct()->pluck('fakultas');
        $jurusanList = Ormawa::whereNotNull('jurusan')->where('jurusan', '!=', '')->distinct()->pluck('jurusan');

        return view('katalog.index', compact('ormawas', 'fakultasList', 'jurusanList'));
    }

    public function show($slug)
    {
        $ormawa = Ormawa::where('slug', $slug)
            ->where('is_active', true)
            ->with(['prestasis' => function($query) {
                $query->orderBy('tahun', 'desc')->latest();
            }, 'programKerjas'])
            ->firstOrFail();

        return view('katalog.show', compact('ormawa'));
    }
}
