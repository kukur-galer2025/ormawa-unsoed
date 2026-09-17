<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Fakultas;
use App\Models\MahasiswaProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusans = Jurusan::with('fakultas')->orderBy('fakultas_id')->orderBy('nama_jurusan')->paginate(10);
        return view('superadmin.jurusan.index', compact('jurusans'));
    }

    public function create()
    {
        $fakultas = Fakultas::orderBy('nama_fakultas')->get();
        return view('superadmin.jurusan.create', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'nama_jurusan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jurusan')->where(function ($query) use ($request) {
                    return $query->where('fakultas_id', $request->fakultas_id);
                }),
            ],
        ]);

        Jurusan::create($request->only('fakultas_id', 'nama_jurusan'));
        return redirect()->route('superadmin.jurusan.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function edit(Jurusan $jurusan)
    {
        $fakultas = Fakultas::orderBy('nama_fakultas')->get();
        return view('superadmin.jurusan.edit', compact('jurusan', 'fakultas'));
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'nama_jurusan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jurusan')->where(function ($query) use ($request) {
                    return $query->where('fakultas_id', $request->fakultas_id);
                })->ignore($jurusan->id),
            ],
        ]);

        $jurusan->update($request->only('fakultas_id', 'nama_jurusan'));
        return redirect()->route('superadmin.jurusan.index')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Jurusan $jurusan)
    {
        if (MahasiswaProfile::where('jurusan_id', $jurusan->id)->exists()) {
            return back()->with('error', 'Tidak dapat menghapus jurusan karena masih dipakai oleh profil mahasiswa.');
        }

        $jurusan->delete();
        return redirect()->route('superadmin.jurusan.index')->with('success', 'Jurusan berhasil dihapus.');
    }
}
