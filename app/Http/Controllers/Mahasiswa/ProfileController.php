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
        return view('mahasiswa.profile.edit', compact('user', 'profile'));
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
            'fakultas' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'angkatan' => 'required|string|size:4',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|max:2048',
        ]);

        $user->update(['name' => $request->name]);

        $profileData = $request->only(['nim', 'fakultas', 'jurusan', 'angkatan', 'no_hp']);

        if ($request->hasFile('foto')) {
            $profileData['foto'] = $request->file('foto')->store('mahasiswa-photos', 'public');
        }

        // Cek apakah ini pertama kali profil dilengkapi (untuk redirect yang tepat)
        $wasIncomplete = !$user->mahasiswaProfile || empty($user->mahasiswaProfile->nim);

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