<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Cek apakah akun aktif (kolom status baru)
            if (isset($user->status) && $user->status === 'nonaktif') {
                Auth::logout();
                return back()->withErrors(['username' => 'Akun Anda telah dinonaktifkan.'])->onlyInput('username');
            }

            // Catat login ke audit log
            try {
                AuditLog::create([
                    'user_id'    => $user->id_user,
                    'aktivitas'  => 'Login',
                    'keterangan' => "Pengguna {$user->username} berhasil login.",
                    'ip_address' => $request->ip(),
                ]);
            } catch (\Throwable) {}

            return $this->redirectByRole($user->role);
        }

        return back()->withErrors([
            'username' => 'Username atau password salah!',
        ])->onlyInput('username');
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        // Catat logout ke audit log
        if ($user) {
            try {
                AuditLog::create([
                    'user_id'    => $user->id_user,
                    'aktivitas'  => 'Logout',
                    'keterangan' => "Pengguna {$user->username} logout.",
                    'ip_address' => $request->ip(),
                ]);
            } catch (\Throwable) {}
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Redirect ke dashboard berdasarkan role.
     */
    private function redirectByRole(string $role)
    {
        return match ($role) {
            'super_admin'            => redirect()->route('superadmin.dashboard'),
            'admin', 'teknisi'       => redirect()->route('admin.dashboard'),
            'dosen', 'mahasiswa'     => redirect()->route('mahasiswa.dashboard'),
            default                  => redirect('/'),
        };
    }
}
