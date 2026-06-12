<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    /**
     * Dashboard mahasiswa.
     */
    public function dashboard()
    {
        $user = Auth::user();
        // Cari data mahasiswa berdasarkan NIM = username
        $mhsModel = Mahasiswa::where('Nim', $user->username)->first();
        $mhs = $mhsModel ?? (object)[
            'Nama_mahasiswa'  => $user->username,
            'Nim'             => $user->username,
            'id_mahasiswa'    => 0,
            'prodi'           => null,
            'foto_profil'     => null,
        ];

        // ── Statistik Laporan ──────────────────────────────────
        $statsQuery = $mhsModel
            ? \App\Models\Laporan::where('id_mahasiswa', $mhsModel->id_mahasiswa)
            : \App\Models\Laporan::whereNull('id_mahasiswa')->where('id_mahasiswa', -1); // empty

        $totalLaporan    = (clone $statsQuery)->count();
        $menunggu        = (clone $statsQuery)->where('Status_terkini', 'Sedang Diperbaiki')->count();
        $selesai         = (clone $statsQuery)->where('Status_terkini', 'Selesai')->count();
        $dalamProses     = $menunggu; // alias untuk UI

        // ── Laporan Terbaru (maks 5) ───────────────────────────
        $laporanTerbaru = $mhsModel
            ? \App\Models\Laporan::with(['kategori', 'lokasi'])
                ->where('id_mahasiswa', $mhsModel->id_mahasiswa)
                ->latest()
                ->take(5)
                ->get()
            : collect();

        // ── Laporan Aktif (untuk progress tracker) ────────────
        $laporanAktif = $mhsModel
            ? \App\Models\Laporan::with(['kategori', 'lokasi'])
                ->where('id_mahasiswa', $mhsModel->id_mahasiswa)
                ->where('Status_terkini', 'Sedang Diperbaiki')
                ->latest()
                ->first()
            : null;

        return view('mahasiswa.dashboard', compact(
            'user', 'mhs',
            'totalLaporan', 'menunggu', 'selesai', 'dalamProses',
            'laporanTerbaru', 'laporanAktif'
        ));
    }

    /**
     * Tampilkan form edit biodata.
     */
    public function biodata($id)
    {
        $mhs = Mahasiswa::findOrFail($id);
        $ukm_array = $mhs->ukm ? explode(', ', $mhs->ukm) : [];
        return view('mahasiswa.biodata', compact('mhs', 'ukm_array'));
    }

    /**
     * Update biodata mahasiswa.
     */
    public function updateBiodata(Request $request, $id)
    {
        $mhs = Mahasiswa::findOrFail($id);

        $request->validate([
            'Nama_mahasiswa' => 'required|string|max:50',
            'Nim'            => 'required|integer',
            'jenis_Kelamin'  => 'required|in:L,P',
            'kontak'         => 'nullable|numeric',
            'foto_profil'    => 'nullable|image|max:2048',
        ]);

        $data = [
            'Nama_mahasiswa' => $request->Nama_mahasiswa,
            'Nim'            => $request->Nim,
            'jenis_kelamin'  => $request->jenis_Kelamin,
            'agama'          => $request->agama,
            'tanggal_lahir'  => $request->tanggal_lahir,
            'prodi'          => $request->prodi,
            'Kontak'         => $request->Kontak,
            'ukm'            => $request->ukm ? implode(', ', $request->ukm) : null,
        ];

        // Handle upload foto
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($mhs->foto_profil && Storage::disk('public')->exists($mhs->foto_profil)) {
                Storage::disk('public')->delete($mhs->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
        }

        $mhs->update($data);

        return redirect()->route('mahasiswa.dashboard')
                         ->with('success', 'Biodata berhasil diperbarui!');
    }

    /**
     * Tampilkan form ganti password.
     */
    public function showGantiPassword()
    {
        $user = Auth::user();
        $mhs  = Mahasiswa::where('Nim', $user->username)->first();
        return view('mahasiswa.ganti-password', compact('user', 'mhs'));
    }

    /**
     * Proses ganti password mahasiswa.
     */
    public function gantiPassword(Request $request)
    {
        $request->validate([
            'password_lama'             => 'required|string',
            'password_baru'             => 'required|string|min:6|confirmed',
            'password_baru_confirmation'=> 'required|string',
        ], [
            'password_lama.required'    => 'Password lama wajib diisi.',
            'password_baru.required'    => 'Password baru wajib diisi.',
            'password_baru.min'         => 'Password baru minimal 6 karakter.',
            'password_baru.confirmed'   => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        // Verifikasi password lama
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama yang Anda masukkan salah.',
            ]);
        }

        // Cek jika password baru sama dengan lama
        if (Hash::check($request->password_baru, $user->password)) {
            return back()->withErrors([
                'password_baru' => 'Password baru tidak boleh sama dengan password lama.',
            ]);
        }

        // Update password di tabel user
        $userModel = User::find($user->id_user);
        $userModel->password = Hash::make($request->password_baru);
        $userModel->save();

        return redirect()->route('mahasiswa.dashboard')
                         ->with('success', 'Password berhasil diubah! Silakan login ulang jika diperlukan.');
    }
}
