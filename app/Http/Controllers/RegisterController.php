<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // ==================== ADMIN ====================

    /**
     * Tampilkan halaman registrasi admin.
     */
    public function showRegisAdmin()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi admin.
     */
    public function regisAdmin(Request $request)
    {
        $request->validate([
            'nama_admin' => 'required|string|max:50',
            'nip'        => 'required|numeric|unique:admin,nip',
            'kontak'     => 'required|numeric',
            'sandi'      => 'required|string|min:6',
        ]);

        $hashedPassword = Hash::make($request->sandi);

        // Simpan ke tabel admin
        Admin::create([
            'nip'        => $request->nip,
            'nama_admin' => $request->nama_admin,
            'sandi'      => $hashedPassword,
            'kontak'     => $request->kontak,
        ]);

        // Buat akun login di tabel user
        $usernameLogin = strtolower(str_replace(' ', '_', $request->nama_admin));

        User::create([
            'username' => $usernameLogin,
            'password' => $hashedPassword,
            'role'     => 'admin',
        ]);

        return redirect('/')->with('success', "Register admin berhasil! Username login: $usernameLogin");
    }

    // ==================== MAHASISWA ====================

    /**
     * Tampilkan halaman registrasi mahasiswa.
     */
    public function showRegisMhs()
    {
        return view('auth.register-mhs');
    }

    /**
     * Proses registrasi mahasiswa.
     */
    public function regisMhs(Request $request)
    {
        $request->validate([
            'nama_mhs'      => 'required|string|max:50',
            'nim'           => 'required|numeric|unique:mahasiswa,Nim',
            'prodi'         => 'required|in:Ilmu Komputer,Sistem Informasi,Matematika,Sains Data',
            'kontak'        => 'required|numeric',
            'sandi'         => 'required|string|min:6',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $hashedPassword = Hash::make($request->sandi);

        // Simpan ke tabel mahasiswa
        Mahasiswa::create([
            'Nim'            => $request->nim,
            'Nama_mahasiswa' => $request->nama_mhs,
            'prodi'          => $request->prodi,
            'Sandi'          => $hashedPassword,
            'Kontak'         => $request->kontak,
            'jenis_kelamin'  => $request->jenis_kelamin,
        ]);

        // Buat akun login di tabel user (username = NIM)
        User::create([
            'username' => (string) $request->nim,
            'password' => $hashedPassword,
            'role'     => 'mahasiswa',
        ]);

        return redirect('/')->with('success', "Register mahasiswa berhasil! Username login Anda: {$request->nim} (NIM)");
    }
}
