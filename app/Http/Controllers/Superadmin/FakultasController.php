<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    public function index()
    {
        $fakultas = Fakultas::withCount('jurusans')->orderBy('nama_fakultas')->paginate(10);
        return view('superadmin.fakultas.index', compact('fakultas'));
    }

    public function create()
    {
        return view('superadmin.fakultas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fakultas' => 'required|string|max:255|unique:fakultas',
        ]);

        Fakultas::create($request->only('nama_fakultas'));
        return redirect()->route('superadmin.fakultas.index')->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function edit(Fakultas $fakulta) // $fakulta is because of Laravel singularization
    {
        return view('superadmin.fakultas.edit', compact('fakulta'));
    }

    public function update(Request $request, Fakultas $fakulta)
    {
        $request->validate([
            'nama_fakultas' => 'required|string|max:255|unique:fakultas,nama_fakultas,' . $fakulta->id,
        ]);

        $fakulta->update($request->only('nama_fakultas'));
        return redirect()->route('superadmin.fakultas.index')->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Fakultas $fakulta)
    {
        if ($fakulta->jurusans()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus fakultas karena masih memiliki jurusan terhubung.');
        }

        $fakulta->delete();
        return redirect()->route('superadmin.fakultas.index')->with('success', 'Fakultas berhasil dihapus.');
    }
}
