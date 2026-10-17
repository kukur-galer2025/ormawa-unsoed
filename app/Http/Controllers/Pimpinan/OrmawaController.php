<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Ormawa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrmawaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Ormawa::visibleTo($user)
            ->with(['fakultasRel', 'jurusanRel'])
            ->withCount(['programKerjas', 'prestasis', 'recruitments']);

        // Filter search nama atau deskripsi
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter tingkat jika wakil_rektor
        if ($tingkat = $request->input('tingkat')) {
            $query->where('tingkat', $tingkat);
        }

        $ormawas = $query->latest()->paginate(12)->withQueryString();

        return view('pimpinan.ormawa.index', compact('ormawas', 'user'));
    }

    public function show(Ormawa $ormawa)
    {
        $user = Auth::user();

        // Scope check: pastikan ormawa termasuk dalam lingkup wewenang user
        abort_unless(
            Ormawa::visibleTo($user)->where('id', $ormawa->id)->exists(),
            404
        );

        $ormawa->load([
            'fakultasRel',
            'jurusanRel',
            'admins',
            'programKerjas' => function ($q) {
                $q->latest();
            },
            'prestasis' => function ($q) {
                $q->orderBy('tahun', 'desc')->latest();
            },
            'recruitments' => function ($q) {
                $q->withCount(['applications', 'divisions'])->latest();
            },
        ]);

        return view('pimpinan.ormawa.show', compact('ormawa', 'user'));
    }
}
