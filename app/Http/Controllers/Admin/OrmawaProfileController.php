<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrmawaProfileController extends Controller
{
    public function edit()
    {
        $ormawa = auth()->user()->ormawas()->first();
        return view('admin.ormawa-profile.edit', compact('ormawa'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'deskripsi' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'kontak_email' => 'nullable|email|max:255',
            'kontak_instagram' => 'nullable|string|max:255',
        ]);

        $ormawa = auth()->user()->ormawas()->first();
        $data = $request->only(['deskripsi', 'visi', 'misi', 'kontak_email', 'kontak_instagram']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('ormawa-logos', 'public');
        }

        $ormawa->update($data);

        return back()->with('success', 'Profil ormawa berhasil diperbarui.');
    }
}