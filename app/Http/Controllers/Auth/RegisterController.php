<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\MahasiswaProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        $fakultas = Fakultas::with('jurusans')->orderBy('nama_fakultas')->get();

        return view('auth.register', compact('fakultas'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'nim'         => 'required|string|size:9|regex:/^[A-Z0-9]{9}$/|unique:profil_mahasiswa,nim',
            'fakultas_id' => 'required|exists:fakultas,id',
            'jurusan_id'  => 'required|exists:jurusan,id',
            'angkatan'    => 'required|integer|digits:4|min:2018|max:2030',
            'no_hp'       => 'nullable|string|regex:/^[0-9]{10,13}$/',
            'password'    => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',    // minimal 1 huruf besar
                'regex:/[\W_]/',    // minimal 1 simbol
            ],
        ], [
            'name.required'        => 'Nama lengkap wajib diisi.',
            'email.required'       => 'Email wajib diisi.',
            'email.email'          => 'Format email tidak valid.',
            'email.unique'         => 'Email ini sudah terdaftar.',
            'nim.required'         => 'NIM wajib diisi.',
            'nim.size'             => 'NIM harus persis 9 karakter.',
            'nim.regex'            => 'NIM hanya boleh berisi huruf besar dan angka.',
            'nim.unique'           => 'NIM ini sudah terdaftar.',
            'fakultas_id.required' => 'Fakultas wajib dipilih.',
            'fakultas_id.exists'   => 'Fakultas yang dipilih tidak valid.',
            'jurusan_id.required'  => 'Jurusan wajib dipilih.',
            'jurusan_id.exists'    => 'Jurusan yang dipilih tidak valid.',
            'angkatan.required'    => 'Angkatan wajib diisi.',
            'angkatan.digits'      => 'Angkatan harus 4 digit angka (misal: 2022).',
            'angkatan.min'         => 'Angkatan minimal tahun 2018.',
            'angkatan.max'         => 'Angkatan maksimal tahun 2030.',
            'no_hp.regex'          => 'No. HP harus berupa angka dan panjangnya antara 10 hingga 13 digit.',
            'password.required'    => 'Kata sandi wajib diisi.',
            'password.min'         => 'Kata sandi minimal 8 karakter.',
            'password.confirmed'   => 'Konfirmasi kata sandi tidak cocok.',
            'password.regex'       => 'Kata sandi harus mengandung minimal 1 huruf besar dan 1 simbol.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'mahasiswa',
        ]);

        MahasiswaProfile::create([
            'user_id'     => $user->id,
            'nim'         => $request->nim,
            'fakultas_id' => $request->fakultas_id,
            'jurusan_id'  => $request->jurusan_id,
            'angkatan'    => $request->angkatan,
            'no_hp'       => $request->no_hp,
        ]);

        Auth::login($user);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }
}