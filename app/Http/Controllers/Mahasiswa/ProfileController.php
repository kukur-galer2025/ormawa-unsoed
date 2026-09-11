<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaProfile;
use Illuminate\Http\Request;

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
            'fakultas' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'angkatan' => 'required|string|size:4',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|max:2048',
        ]);

        $user->update(['name' => $request->name]);

        $profileData = $request->only(['fakultas', 'jurusan', 'angkatan', 'no_hp']);

        if ($request->hasFile('foto')) {
            $profileData['foto'] = $request->file('foto')->store('mahasiswa-photos', 'public');
        }

        $user->mahasiswaProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}