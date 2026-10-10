<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrmawaAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            return $next($request);
        }

        if (!$user->ormawa) {
            // Allow access to dashboard so they can see the no-ormawa message
            if ($request->routeIs('admin.dashboard')) {
                return $next($request);
            }
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda belum di-assign ke organisasi manapun.');
        }

        if (!$user->ormawa->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Organisasi Anda telah dinonaktifkan oleh sistem.');
        }

        return $next($request);
    }
}