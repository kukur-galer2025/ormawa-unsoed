<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrmawaPrestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    public function index()
    {
        $ormawa = auth()->user()->ormawas()->first();
        $prestasis = $ormawa->prestasis()->latest()->get();
        return view('admin.prestasi.index', compact('prestasis', 'ormawa'));
    }

    public function create()
    {
        return view('admin.prestasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tahun' => 'nullable|integer|min:2000|max:'.(date('Y')+1),
            'foto' => 'nullable|image|max:2048'
        ]);

        $ormawa = auth()->user()->ormawas()->first();
        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('prestasi-foto', 'public');
        }

        $ormawa->prestasis()->create($data);

        return redirect()->route('admin.prestasi.index')->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function edit(OrmawaPrestasi $prestasi)
    {
        $ormawa = auth()->user()->ormawas()->first();
        if ($prestasi->ormawa_id !== $ormawa->id) abort(403);

        return view('admin.prestasi.edit', compact('prestasi'));
    }

    public function update(Request $request, OrmawaPrestasi $prestasi)
    {
        $ormawa = auth()->user()->ormawas()->first();
        if ($prestasi->ormawa_id !== $ormawa->id) abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tahun' => 'nullable|integer|min:2000|max:'.(date('Y')+1),
            'foto' => 'nullable|image|max:2048'
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            if ($prestasi->foto) Storage::disk('public')->delete($prestasi->foto);
            $data['foto'] = $request->file('foto')->store('prestasi-foto', 'public');
        }

        $prestasi->update($data);

        return redirect()->route('admin.prestasi.index')->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy(OrmawaPrestasi $prestasi)
    {
        $ormawa = auth()->user()->ormawas()->first();
        if ($prestasi->ormawa_id !== $ormawa->id) abort(403);

        if ($prestasi->foto) Storage::disk('public')->delete($prestasi->foto);
        $prestasi->delete();

        return redirect()->route('admin.prestasi.index')->with('success', 'Prestasi berhasil dihapus.');
    }
}
