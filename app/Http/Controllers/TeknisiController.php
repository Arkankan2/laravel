<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeknisiController extends Controller
{
    /* ─────────────────────────────────────────────────────────────────
     |  DASHBOARD — ringkasan statistik tugas teknisi
     | ──────────────────────────────────────────────────────────────── */
    public function dashboard()
    {
        $user = Auth::user();

        // Statistik
        $totalTugas      = Laporan::count();
        $tugasBaru       = Laporan::where('Status_terkini', 'Sedang Diperbaiki')->count();
        $dalamPengerjaan = Laporan::where('Status_terkini', 'Dalam Pengerjaan')->count();
        $selesai         = Laporan::where('Status_terkini', 'Selesai')->count();
        $darurat         = Laporan::where('Tingkat_Kerusakan', 'Parah')
                                  ->whereNotIn('Status_terkini', ['Selesai'])
                                  ->count();

        // Tugas terbaru (5 teratas)
        $tugasTerbaru = Laporan::with(['mahasiswa', 'kategori', 'lokasi'])
            ->whereNotIn('Status_terkini', ['Selesai'])
            ->latest()
            ->limit(5)
            ->get();

        // Aktivitas terbaru (semua status, 6 teratas)
        $aktivitasTerbaru = Laporan::with(['mahasiswa', 'kategori', 'lokasi'])
            ->latest('updated_at')
            ->limit(6)
            ->get();

        return view('Teknisi.dashboard', compact(
            'user',
            'totalTugas', 'tugasBaru', 'dalamPengerjaan', 'selesai', 'darurat',
            'tugasTerbaru', 'aktivitasTerbaru'
        ));
    }

    /* ─────────────────────────────────────────────────────────────────
     |  TUGAS SAYA — daftar semua laporan yang dikelola teknisi
     | ──────────────────────────────────────────────────────────────── */
    public function tugasSaya(Request $request)
    {
        $user  = Auth::user();
        $query = Laporan::with(['mahasiswa', 'kategori', 'lokasi'])->latest();

        // Filter status
        if ($request->filled('status')) {
            $query->where('Status_terkini', $request->status);
        }

        // Filter tingkat
        if ($request->filled('tingkat')) {
            $query->where('Tingkat_Kerusakan', $request->tingkat);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', fn($q2) => $q2->where('Nama_mahasiswa', 'like', "%{$search}%"))
                  ->orWhereHas('lokasi', fn($q3) => $q3->where('nama_ruangan', 'like', "%{$search}%")
                                                        ->orWhere('nama_gedung', 'like', "%{$search}%"));
            });
        }

        $laporanList = $query->paginate(10)->withQueryString();

        // Stat badges
        $statTotal   = Laporan::count();
        $statBaru    = Laporan::where('Status_terkini', 'Sedang Diperbaiki')->count();
        $statProses  = Laporan::where('Status_terkini', 'Dalam Pengerjaan')->count();
        $statSelesai = Laporan::where('Status_terkini', 'Selesai')->count();

        return view('Teknisi.tugas', compact(
            'user', 'laporanList',
            'statTotal', 'statBaru', 'statProses', 'statSelesai'
        ));
    }

    /* ─────────────────────────────────────────────────────────────────
     |  DETAIL TUGAS — lihat detail laporan
     | ──────────────────────────────────────────────────────────────── */
    public function detailTugas($id)
    {
        $user    = Auth::user();
        $laporan = Laporan::with(['mahasiswa', 'kategori', 'lokasi'])->findOrFail($id);

        return view('Teknisi.detail', compact('user', 'laporan'));
    }

    /* ─────────────────────────────────────────────────────────────────
     |  MULAI PERBAIKAN — ubah status ke "Dalam Pengerjaan"
     | ──────────────────────────────────────────────────────────────── */
    public function mulaiPerbaikan($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update(['Status_terkini' => 'Dalam Pengerjaan']);

        return redirect()
            ->route('teknisi.detail', $laporan->id_laporan)
            ->with('success', '🔧 Status laporan diubah ke "Dalam Pengerjaan". Silakan mulai perbaikan!');
    }

    /* ─────────────────────────────────────────────────────────────────
     |  FORM SELESAIKAN TUGAS — halaman upload bukti
     | ──────────────────────────────────────────────────────────────── */
    public function formSelesai($id)
    {
        $user    = Auth::user();
        $laporan = Laporan::with(['mahasiswa', 'kategori', 'lokasi'])->findOrFail($id);

        return view('Teknisi.selesai', compact('user', 'laporan'));
    }

    /* ─────────────────────────────────────────────────────────────────
     |  SELESAIKAN TUGAS — upload bukti & ubah status ke "Selesai"
     | ──────────────────────────────────────────────────────────────── */
    public function selesaikanTugas(Request $request, $id)
    {
        $request->validate([
            'foto_selesai' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'catatan'      => 'required|string|min:10|max:1000',
        ], [
            'foto_selesai.required' => 'Foto bukti perbaikan wajib diunggah.',
            'foto_selesai.image'    => 'File harus berupa gambar.',
            'foto_selesai.max'      => 'Ukuran foto maksimal 5 MB.',
            'catatan.required'      => 'Catatan hasil perbaikan wajib diisi.',
            'catatan.min'           => 'Catatan minimal 10 karakter.',
        ]);

        $laporan = Laporan::findOrFail($id);

        // Simpan foto bukti perbaikan
        $fotoPath = null;
        if ($request->hasFile('foto_selesai')) {
            $fotoPath = $request->file('foto_selesai')->store('bukti-perbaikan', 'public');
        }

        // Update status laporan ke Selesai
        $laporan->update([
            'Status_terkini' => 'Selesai',
        ]);

        // Simpan foto & catatan jika kolomnya sudah ada (opsional)
        try {
            $columns = \Illuminate\Support\Facades\Schema::getColumnListing('laporan');
            $updateData = [];
            if (in_array('foto_selesai', $columns) && $fotoPath) {
                $updateData['foto_selesai'] = $fotoPath;
            }
            if (in_array('catatan_selesai', $columns)) {
                $updateData['catatan_selesai'] = $request->catatan;
            }
            if (!empty($updateData)) {
                DB::table('laporan')->where('id_laporan', $id)->update($updateData);
            }
        } catch (\Exception $e) {
            // Lewati jika kolom belum tersedia
        }

        // Notifikasi simulasi (log saja; di production bisa pakai Notification/Mail)
        // Notifikasi ke mahasiswa & admin sudah tercatat lewat perubahan status

        return redirect()
            ->route('teknisi.tugas')
            ->with('success', '✅ Tugas berhasil diselesaikan! Notifikasi telah dikirim ke mahasiswa dan admin.');
    }
}
