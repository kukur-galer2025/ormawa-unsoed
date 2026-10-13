<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek dulu apakah email terdaftar di sistem
        $userExists = User::where('email', $credentials['email'])->exists();
        if (!$userExists) {
            return back()
                ->with('error', 'Email tidak terdaftar di sistem.')
                ->withInput($request->only('email'));
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
            }

            if ($user->role === 'admin' && $user->ormawa && !$user->ormawa->is_active) {
                Auth::logout();
                return back()->with('error', 'Organisasi Anda sedang dinonaktifkan. Anda tidak dapat mengakses sistem.');
            }

            return $this->redirectByRole($user);
        }

        return back()->with('error', 'Password yang Anda masukkan salah.')->withInput($request->only('email'));
    }

    protected function redirectByRole($user)
    {
        return match ($user->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            default => redirect('/login'),
        };
    }
}