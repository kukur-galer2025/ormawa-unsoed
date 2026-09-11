<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrmawaProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramKerjaController extends Controller
{
    public function index()
    {
        $ormawa = auth()->user()->ormawas()->first();
        $prokers = $ormawa->programKerjas()->latest()->get();
        return view('admin.proker.index', compact('prokers', 'ormawa'));
    }

    public function create()
    {
        return view('admin.proker.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048'
        ]);

        $ormawa = auth()->user()->ormawas()->first();
        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('proker-foto', 'public');
        }

        $ormawa->programKerjas()->create($data);

        return redirect()->route('admin.proker.index')->with('success', 'Program Kerja berhasil ditambahkan.');
    }

    public function edit(OrmawaProgramKerja $proker)
    {
        $ormawa = auth()->user()->ormawas()->first();
        if ($proker->ormawa_id !== $ormawa->id) abort(403);

        return view('admin.proker.edit', compact('proker'));
    }

    public function update(Request $request, OrmawaProgramKerja $proker)
    {
        $ormawa = auth()->user()->ormawas()->first();
        if ($proker->ormawa_id !== $ormawa->id) abort(403);

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048'
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            if ($proker->foto) Storage::disk('public')->delete($proker->foto);
            $data['foto'] = $request->file('foto')->store('proker-foto', 'public');
        }

        $proker->update($data);

        return redirect()->route('admin.proker.index')->with('success', 'Program Kerja berhasil diperbarui.');
    }

    public function destroy(OrmawaProgramKerja $proker)
    {
        $ormawa = auth()->user()->ormawas()->first();
        if ($proker->ormawa_id !== $ormawa->id) abort(403);

        if ($proker->foto) Storage::disk('public')->delete($proker->foto);
        $proker->delete();

        return redirect()->route('admin.proker.index')->with('success', 'Program Kerja berhasil dihapus.');
    }
}
