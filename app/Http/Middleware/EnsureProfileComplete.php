<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    /**
     * Middleware untuk memaksa mahasiswa melengkapi profil (NIM, fakultas, jurusan)
     * sebelum bisa mengakses fitur lain di sistem.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Hanya berlaku untuk role mahasiswa
        if ($user->role !== 'mahasiswa') {
            return $next($request);
        }

        $profile = $user->mahasiswaProfile;

        // Cek apakah profil belum ada, atau NIM/fakultas/jurusan masih kosong
        $isIncomplete = !$profile
            || empty($profile->nim)
            || empty($profile->fakultas)
            || empty($profile->jurusan);

        if ($isIncomplete) {
            // Izinkan akses ke halaman edit profil, update profil, dan logout
            $allowedRoutes = [
                'mahasiswa.profile.edit',
                'mahasiswa.profile.update',
                'logout',
            ];

            if (!$request->routeIs(...$allowedRoutes)) {
                return redirect()->route('mahasiswa.profile.edit')
                    ->with('error', 'Silakan lengkapi profil Anda (NIM, fakultas, jurusan) terlebih dahulu sebelum menggunakan fitur ini.');
            }
        }

        return $next($request);
    }
}
