<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MahasiswaProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to Google's OAuth page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback from Google after authentication.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal mengautentikasi dengan Google. Silakan coba lagi.');
        }

        // Check if user already exists by google_id or email
        $user = User::where('google_id', $googleUser->getId())
                     ->orWhere('email', $googleUser->getEmail())
                     ->first();

        if ($user) {
            // Update google_id and avatar if not set yet (e.g., existing user linking Google)
            $user->update([
                'google_id' => $googleUser->getId(),
                'google_avatar' => $googleUser->getAvatar(),
            ]);
        } else {
            // Create new user as mahasiswa
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'google_avatar' => $googleUser->getAvatar(),
                'password' => bcrypt(Str::random(24)), // random password since they use Google
                'role' => 'mahasiswa',
                'is_active' => true,
            ]);

            // Create empty mahasiswa profile
            MahasiswaProfile::create([
                'user_id' => $user->id,
                'nim' => '',
                'fakultas' => '',
                'jurusan' => '',
                'angkatan' => '',
            ]);
        }

        Auth::login($user, true);

        // Redirect based on role
        return match ($user->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('mahasiswa.dashboard'),
        };
    }
}
