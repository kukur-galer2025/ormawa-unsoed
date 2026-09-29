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
        $ormawas = Ormawa::with(['fakultasRel', 'jurusanRel'])->withCount(['recruitments', 'admins'])->latest()->paginate(10);
        $fakultas = Fakultas::with('jurusans')->get();
        return view('superadmin.ormawa.index', compact('ormawas', 'fakultas'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:ormawa,nama',
            'tingkat' => 'required|in:Universitas,Fakultas,Jurusan',
            'fakultas_id' => 'required_if:tingkat,Fakultas,Jurusan|nullable|exists:fakultas,id',
            'jurusan_id' => 'required_if:tingkat,Jurusan|nullable|exists:jurusan,id',
        ]);

        $data = $request->only(['nama', 'tingkat', 'fakultas_id', 'jurusan_id']);
        $slug = Str::slug($request->nama);
        $originalSlug = $slug;
        $counter = 1;
        while (Ormawa::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        $data['slug'] = $slug;

        if ($data['tingkat'] === 'Universitas') {
            $data['fakultas_id'] = null;
            $data['jurusan_id'] = null;
        } elseif ($data['tingkat'] === 'Fakultas') {
            $data['jurusan_id'] = null;
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
            'nama' => 'required|string|max:255|unique:ormawa,nama,' . $ormawa->id,
            'tingkat' => 'required|in:Universitas,Fakultas,Jurusan',
            'fakultas_id' => 'required_if:tingkat,Fakultas,Jurusan|nullable|exists:fakultas,id',
            'jurusan_id' => 'required_if:tingkat,Jurusan|nullable|exists:jurusan,id',
            'deskripsi' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'cover_photo' => 'nullable|image|max:4096',
            'kontak_email' => 'nullable|email',
            'kontak_instagram' => 'nullable|string|max:255',
        ]);

        $data = $request->except(['logo', 'cover_photo']);
        
        if ($data['tingkat'] === 'Universitas') {
            $data['fakultas_id'] = null;
            $data['jurusan_id'] = null;
        } elseif ($data['tingkat'] === 'Fakultas') {
            $data['jurusan_id'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($ormawa->logo && \Storage::disk('public')->exists($ormawa->logo)) {
                \Storage::disk('public')->delete($ormawa->logo);
            }
            $data['logo'] = $request->file('logo')->store('ormawa-logos', 'public');
        }

        if ($request->hasFile('cover_photo')) {
            // Delete old cover if exists
            if ($ormawa->cover_photo && \Storage::disk('public')->exists($ormawa->cover_photo)) {
                \Storage::disk('public')->delete($ormawa->cover_photo);
            }
            $data['cover_photo'] = $request->file('cover_photo')->store('ormawa-covers', 'public');
        }

        $ormawa->update($data);

        return redirect()->route('superadmin.ormawa.index')->with('success', 'Ormawa berhasil diperbarui.');
    }

    public function destroy(Ormawa $ormawa)
    {
        // Bersihkan berkas pendaftar (berkas_pendukung)
        $applications = \App\Models\Application::whereHas('recruitment', function($q) use ($ormawa) {
            $q->where('ormawa_id', $ormawa->id);
        })->get();
        
        foreach ($applications as $app) {
            if ($app->berkas_pendukung && \Storage::disk('public')->exists($app->berkas_pendukung)) {
                \Storage::disk('public')->delete($app->berkas_pendukung);
            }
        }

        // Hapus foto prestasi dan proker
        foreach ($ormawa->prestasis as $prestasi) {
            if ($prestasi->foto && \Storage::disk('public')->exists($prestasi->foto)) {
                \Storage::disk('public')->delete($prestasi->foto);
            }
        }
        foreach ($ormawa->programKerjas as $proker) {
            if ($proker->foto && \Storage::disk('public')->exists($proker->foto)) {
                \Storage::disk('public')->delete($proker->foto);
            }
        }

        // Bersihkan aset ormawa
        if ($ormawa->logo && \Storage::disk('public')->exists($ormawa->logo)) {
            \Storage::disk('public')->delete($ormawa->logo);
        }
        if ($ormawa->cover_photo && \Storage::disk('public')->exists($ormawa->cover_photo)) {
            \Storage::disk('public')->delete($ormawa->cover_photo);
        }

        $ormawa->delete();
        return redirect()->route('superadmin.ormawa.index')->with('success', 'Ormawa berhasil dihapus.');
    }
}