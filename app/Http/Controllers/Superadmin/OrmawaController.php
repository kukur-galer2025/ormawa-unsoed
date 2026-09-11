<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Ormawa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrmawaController extends Controller
{
    public function index()
    {
        $ormawas = Ormawa::withCount(['recruitments', 'admins'])->latest()->paginate(10);
        return view('superadmin.ormawa.index', compact('ormawas'));
    }

    public function create()
    {
        return view('superadmin.ormawa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tingkat' => 'required|in:Universitas,Fakultas,Jurusan',
            'fakultas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'visi_misi' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'kontak_email' => 'nullable|email',
            'kontak_instagram' => 'nullable|string|max:255',
        ]);

        $data = $request->except('logo');
        $data['slug'] = Str::slug($request->nama);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('ormawa-logos', 'public');
        }

        Ormawa::create($data);

        return redirect()->route('superadmin.ormawa.index')->with('success', 'Ormawa berhasil ditambahkan.');
    }

    public function show(Ormawa $ormawa)
    {
        $ormawa->load(['admins', 'recruitments']);
        return view('superadmin.ormawa.show', compact('ormawa'));
    }

    public function edit(Ormawa $ormawa)
    {
        return view('superadmin.ormawa.edit', compact('ormawa'));
    }

    public function update(Request $request, Ormawa $ormawa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tingkat' => 'required|in:Universitas,Fakultas,Jurusan',
            'fakultas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'visi_misi' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'kontak_email' => 'nullable|email',
            'kontak_instagram' => 'nullable|string|max:255',
        ]);

        $data = $request->except('logo');
        $data['slug'] = Str::slug($request->nama);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('ormawa-logos', 'public');
        }

        $ormawa->update($data);

        return redirect()->route('superadmin.ormawa.index')->with('success', 'Ormawa berhasil diperbarui.');
    }

    public function destroy(Ormawa $ormawa)
    {
        $ormawa->delete();
        return redirect()->route('superadmin.ormawa.index')->with('success', 'Ormawa berhasil dihapus.');
    }
}