<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Hanya mengizinkan pengguna dengan role super_admin.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== 'super_admin') {
            if (in_array($user->role, ['admin', 'teknisi'])) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('mahasiswa.dashboard');
        }

        // Cek apakah akun aktif
        if (isset($user->status) && $user->status === 'nonaktif') {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['username' => 'Akun Anda telah dinonaktifkan.']);
        }

        return $next($request);
    }
}
