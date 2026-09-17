<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Ormawa;
use App\Models\Fakultas;
use App\Models\Jurusan;
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
        $fakultas = Fakultas::with('jurusans')->get();
        return view('superadmin.ormawa.create', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tingkat' => 'required|in:Universitas,Fakultas,Jurusan',
            'fakultas_id' => 'required_if:tingkat,Fakultas,Jurusan|nullable|exists:fakultas,id',
            'jurusan_id' => 'required_if:tingkat,Jurusan|nullable|exists:jurusan,id',
            'deskripsi' => 'nullable|string',
            'visi_misi' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'kontak_email' => 'nullable|email',
            'kontak_instagram' => 'nullable|string|max:255',
        ]);

        $data = $request->except('logo');
        $data['slug'] = Str::slug($request->nama);
        
        if ($data['tingkat'] === 'Universitas') {
            $data['fakultas_id'] = null;
            $data['jurusan_id'] = null;
        } elseif ($data['tingkat'] === 'Fakultas') {
            $data['jurusan_id'] = null;
        }

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
        $fakultas = Fakultas::with('jurusans')->get();
        return view('superadmin.ormawa.edit', compact('ormawa', 'fakultas'));
    }

    public function update(Request $request, Ormawa $ormawa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tingkat' => 'required|in:Universitas,Fakultas,Jurusan',
            'fakultas_id' => 'required_if:tingkat,Fakultas,Jurusan|nullable|exists:fakultas,id',
            'jurusan_id' => 'required_if:tingkat,Jurusan|nullable|exists:jurusan,id',
            'deskripsi' => 'nullable|string',
            'visi_misi' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'kontak_email' => 'nullable|email',
            'kontak_instagram' => 'nullable|string|max:255',
        ]);

        $data = $request->except('logo');
        $data['slug'] = Str::slug($request->nama);
        
        if ($data['tingkat'] === 'Universitas') {
            $data['fakultas_id'] = null;
            $data['jurusan_id'] = null;
        } elseif ($data['tingkat'] === 'Fakultas') {
            $data['jurusan_id'] = null;
        }

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