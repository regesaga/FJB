<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotSuspended
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isSuspended()) {
            return $next($request);
        }

        // Logout hanya jika akun tersebut menggunakan sesi web.
        $webUser = Auth::guard('web')->user();

        if ($webUser && $webUser->id === $user->id) {
            Auth::guard('web')->logout();

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }

        // Token Sanctum tidak dihapus, tetapi aksesnya ditolak.
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda ditangguhkan. Silakan hubungi administrator FJB.',
            ], 403);
        }

        return redirect()->route('login')->withErrors([
            'email' => 'Akun Anda ditangguhkan. Silakan hubungi administrator FJB.',
        ]);
    }
}