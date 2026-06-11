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

    /**
     * Tampilkan halaman Riwayat Laporan.
     * Data $laporanList saat ini menggunakan dummy — ganti dengan
     * query ke tabel laporan ketika model Laporan sudah dibuat.
     */
    public function riwayat()
    {
        $user  = Auth::user();
        $admin = Admin::where('nama_admin', 'like', '%' . $user->username . '%')->first()
                 ?? (object)['nama_admin' => $user->username];

        // ── DUMMY DATA ──────────────────────────────────────────────
        // Ganti bagian ini dengan query database, contoh:
        // $laporanList = Laporan::with('lokasi')->latest()->get()->map(...);
        $laporanList = [
            [
                'id'          => 1,
                'deskripsi'   => 'Kursi di ruang 201 patah dan tidak bisa dipakai',
                'lokasi'      => 'Kampus 2, Ruang Laboratorium 201',
                'status'      => 'SELESAI',
                'status_key'  => 'selesai',
                'foto'        => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?auto=format&fit=crop&q=80&w=600',
                'tanggal'     => '2026-06-05 09:00',
            ],
            [
                'id'          => 2,
                'deskripsi'   => 'Meja kayu retak parah di ruang kelas 305',
                'lokasi'      => 'Kampus 2, Ruang Laboratorium 305',
                'status'      => 'DALAM PERBAIKAN',
                'status_key'  => 'perbaikan',
                'foto'        => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&q=80&w=600',
                'tanggal'     => '2026-06-07 11:30',
            ],
            [
                'id'          => 3,
                'deskripsi'   => 'AC tidak dingin dan mengeluarkan suara berisik',
                'lokasi'      => 'Kampus 1, Ruang Dosen Lt. 2',
                'status'      => 'PERBAIKAN DITUNDA',
                'status_key'  => 'ditunda',
                'foto'        => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&q=80&w=600',
                'tanggal'     => '2026-06-08 14:15',
            ],
            [
                'id'          => 4,
                'deskripsi'   => 'Lampu koridor B mati total, berbahaya malam hari',
                'lokasi'      => 'Kampus 1, Koridor Gedung B',
                'status'      => 'SELESAI',
                'status_key'  => 'selesai',
                'foto'        => 'https://images.unsplash.com/photo-1550985616-10810253b84d?auto=format&fit=crop&q=80&w=600',
                'tanggal'     => '2026-06-09 08:45',
            ],
            [
                'id'          => 5,
                'deskripsi'   => 'Pintu toilet wanita rusak, tidak bisa dikunci',
                'lokasi'      => 'Kampus 2, Toilet Gedung A Lt.1',
                'status'      => 'DALAM PERBAIKAN',
                'status_key'  => 'perbaikan',
                'foto'        => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&q=80&w=600',
                'tanggal'     => '2026-06-10 13:20',
            ],
            [
                'id'          => 6,
                'deskripsi'   => 'Proyektor tidak menyala, kabel HDMI rusak',
                'lokasi'      => 'Kampus 2, Ruang Laboratorium 301',
                'status'      => 'PERBAIKAN DITUNDA',
                'status_key'  => 'ditunda',
                'foto'        => 'https://images.unsplash.com/photo-1516387938699-a927021e7700?auto=format&fit=crop&q=80&w=600',
                'tanggal'     => '2026-06-10 16:00',
            ],
            [
                'id'          => 7,
                'deskripsi'   => 'Tembok retak di dekat tangga darurat gedung C',
                'lokasi'      => 'Kampus 1, Gedung C Lt. 3',
                'status'      => 'SELESAI',
                'status_key'  => 'selesai',
                'foto'        => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&q=80&w=600',
                'tanggal'     => '2026-06-11 09:00',
            ],
        ];
        // ── AKHIR DUMMY DATA ─────────────────────────────────────────

        return view('admin.riwayat', compact('user', 'admin', 'laporanList'));
    }
}
