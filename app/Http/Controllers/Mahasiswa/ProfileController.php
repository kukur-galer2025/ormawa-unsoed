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
        $fakultasList = \App\Models\Fakultas::with('jurusans')->orderBy('nama_fakultas')->get();

        return view('mahasiswa.profile.edit', compact('user', 'profile', 'fakultasList'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if ($request->has('nim')) {
            $request->merge(['nim' => strtoupper($request->nim)]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => [
                'required',
                'string',
                'size:9',
                'regex:/^[A-Z0-9]{9}$/',
                Rule::unique('profil_mahasiswa', 'nim')->ignore($user->id, 'user_id'),
            ],
            'fakultas_id' => 'required|exists:fakultas,id',
            'jurusan_id' => 'required|exists:jurusan,id',
            'angkatan' => 'required|integer|digits:4|min:2020',
            'no_hp' => 'nullable|string|regex:/^[0-9]{10,13}$/',
            'foto' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.size' => 'NIM harus persis 9 karakter.',
            'nim.regex' => 'Format NIM tidak valid. Gunakan huruf kapital dan angka.',
            'nim.unique' => 'NIM tersebut sudah terdaftar.',
            'fakultas_id.required' => 'Fakultas wajib dipilih.',
            'jurusan_id.required' => 'Jurusan wajib dipilih.',
            'angkatan.required' => 'Angkatan wajib diisi.',
            'angkatan.digits' => 'Angkatan harus 4 digit angka (misal: 2022).',
            'angkatan.min' => 'Angkatan minimal tahun 2020.',
            'no_hp.regex' => 'No. HP harus berupa angka dan panjangnya antara 10 hingga 13 digit.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
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