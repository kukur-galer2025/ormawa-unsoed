<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->mahasiswaProfile ?? new MahasiswaProfile(['user_id' => $user->id]);
        $fakultasList = \App\Models\Fakultas::orderBy('nama_fakultas')->get();
        $jurusanList = \App\Models\Jurusan::orderBy('nama_jurusan')->get();

        return view('mahasiswa.profile.edit', compact('user', 'profile', 'fakultasList', 'jurusanList'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('profil_mahasiswa', 'nim')->ignore($user->id, 'user_id'),
            ],
            'fakultas_id' => 'required|exists:fakultas,id',
            'jurusan_id' => 'required|exists:jurusan,id',
            'angkatan' => 'required|integer|digits:4|min:2015|max:' . now()->year,
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|max:2048',
        ]);

        $user->update(['name' => $request->name]);

        $profileData = $request->only(['nim', 'fakultas_id', 'jurusan_id', 'angkatan', 'no_hp']);

        if ($request->hasFile('foto')) {
            $profileData['foto'] = $request->file('foto')->store('mahasiswa-photos', 'public');
        }

        // Cek apakah ini pertama kali profil dilengkapi (untuk redirect yang tepat)
        $wasIncomplete = !$user->mahasiswaProfile || empty($user->mahasiswaProfile->nim) || empty($user->mahasiswaProfile->fakultas_id) || empty($user->mahasiswaProfile->jurusan_id);

        $user->mahasiswaProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        if ($wasIncomplete) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('success', 'Profil berhasil dilengkapi, silakan lanjutkan menggunakan sistem.');
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}