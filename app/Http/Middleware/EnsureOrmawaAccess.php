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

        // Check if admin has any ormawa assigned
        if ($user->ormawas()->count() === 0) {
            // Allow access to dashboard so they can see the no-ormawa message
            if ($request->routeIs('admin.dashboard')) {
                return $next($request);
            }
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda belum di-assign ke organisasi manapun.');
        }

        return $next($request);
    }
}