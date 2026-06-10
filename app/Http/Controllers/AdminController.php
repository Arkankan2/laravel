<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Dashboard admin: tampilkan data admin yang sedang login.
     */
    public function dashboard()
    {
        $user = Auth::user();
        // Cari data admin berdasarkan username yang sesuai
        $admin = Admin::where('nama_admin', 'like', '%' . $user->username . '%')->first()
                 ?? (object)['nama_admin' => $user->username];

        return view('admin.dashboard', compact('user', 'admin'));
    }

    /**
     * Tampilkan daftar mahasiswa.
     */
    public function daftarMahasiswa()
    {
        $mahasiswas = Mahasiswa::all();
        return view('admin.mahasiswa', compact('mahasiswas'));
    }

    /**
     * Hapus mahasiswa & akun user-nya.
     */
    public function hapusMahasiswa($id)
    {
        $mhs = Mahasiswa::findOrFail($id);

        // Hapus akun login (user) yang NIM-nya cocok
        User::where('username', (string) $mhs->Nim)->delete();

        $mhs->delete();

        return redirect()->route('admin.mahasiswa')
                         ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
