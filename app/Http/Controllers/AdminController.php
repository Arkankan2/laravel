<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Laporan;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Dashboard admin: tampilkan data lengkap untuk dashboard profesional.
     */
    public function dashboard()
    {
        $user  = Auth::user();
        $admin = Admin::where('nama_admin', 'like', '%' . $user->username . '%')->first()
                 ?? (object)['nama_admin' => $user->username];

        // ── Statistik Utama ──────────────────────────────────────────
        $totalLaporan    = Laporan::count();
        $menunggu        = Laporan::where('Status_terkini', 'Sedang Diperbaiki')->count();
        $dalamPengerjaan = Laporan::where('Status_terkini', 'Sedang Diperbaiki')->count();
        $selesai         = Laporan::where('Status_terkini', 'Selesai')->count();

        // ── Laporan Terbaru (5 terakhir) ─────────────────────────────
        $laporanTerbaru = Laporan::with(['mahasiswa', 'kategori', 'lokasi'])
            ->latest()
            ->limit(5)
            ->get();

        // ── Laporan Prioritas Tinggi (Parah) ─────────────────────────
        $laporanPrioritas = Laporan::with(['lokasi', 'kategori'])
            ->where('Tingkat_Kerusakan', 'Parah')
            ->where('Status_terkini', 'Sedang Diperbaiki')
            ->latest()
            ->limit(5)
            ->get();

        // ── Laporan per bulan (tahun ini) ────────────────────────────
        $laporanBulanan = Laporan::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $chartBulan = array_fill(0, 12, 0);
        foreach ($laporanBulanan as $row) {
            $chartBulan[$row->bulan - 1] = $row->total;
        }

        // ── Kategori terbanyak ───────────────────────────────────────
        $kategoriStats = Laporan::select('id_kategori', DB::raw('COUNT(*) as total'))
            ->with('kategori')
            ->groupBy('id_kategori')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $chartKategoriLabels = $kategoriStats->map(fn($k) => $k->kategori?->nama_kategori ?? 'Lainnya')->toArray();
        $chartKategoriData   = $kategoriStats->pluck('total')->toArray();

        // ── Notifikasi dummy (bisa dihubungkan ke data nyata) ────────
        $notifikasi = [];

        // Laporan baru hari ini
        $baruHariIni = Laporan::whereDate('created_at', today())->count();
        if ($baruHariIni > 0) {
            $notifikasi[] = [
                'type'  => 'info',
                'icon'  => 'fa-file-circle-plus',
                'pesan' => "$baruHariIni laporan baru masuk hari ini",
                'waktu' => 'Hari ini',
            ];
        }

        // Laporan prioritas tinggi yang belum selesai
        $prioritasCount = $laporanPrioritas->count();
        if ($prioritasCount > 0) {
            $notifikasi[] = [
                'type'  => 'danger',
                'icon'  => 'fa-triangle-exclamation',
                'pesan' => "$prioritasCount laporan prioritas tinggi belum ditangani",
                'waktu' => 'Perlu segera',
            ];
        }

        // Laporan lebih dari 3 hari belum selesai
        $telantar = Laporan::where('Status_terkini', 'Sedang Diperbaiki')
            ->where('created_at', '<', now()->subDays(3))
            ->count();
        if ($telantar > 0) {
            $notifikasi[] = [
                'type'  => 'warning',
                'icon'  => 'fa-clock',
                'pesan' => "$telantar laporan belum ditangani lebih dari 3 hari",
                'waktu' => '> 3 hari lalu',
            ];
        }

        // Fallback jika tidak ada notifikasi
        if (empty($notifikasi)) {
            $notifikasi[] = [
                'type'  => 'success',
                'icon'  => 'fa-circle-check',
                'pesan' => 'Semua laporan dalam kondisi terkendali',
                'waktu' => 'Sekarang',
            ];
        }

        return view('admin.dashboard', compact(
            'user', 'admin',
            'totalLaporan', 'menunggu', 'dalamPengerjaan', 'selesai',
            'laporanTerbaru', 'laporanPrioritas',
            'chartBulan', 'chartKategoriLabels', 'chartKategoriData',
            'notifikasi',
        ));
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
     * Tampilkan halaman Riwayat Laporan — data dari database.
     */
    public function riwayat(Request $request)
    {
        $user  = Auth::user();
        $admin = Admin::where('nama_admin', 'like', '%' . $user->username . '%')->first()
                 ?? (object)['nama_admin' => $user->username];

        // ── Query dengan filter & pencarian ──────────────────────────
        $query = Laporan::with(['mahasiswa', 'kategori', 'lokasi'])->latest();

        // Filter: Status
        if ($request->filled('status')) {
            $query->where('Status_terkini', $request->status);
        }

        // Filter: Tingkat Kerusakan
        if ($request->filled('tingkat')) {
            $query->where('Tingkat_Kerusakan', $request->tingkat);
        }

        // Search: deskripsi atau nama pelapor
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', fn($q2) =>
                      $q2->where('Nama_mahasiswa', 'like', "%{$search}%")
                  )
                  ->orWhereHas('lokasi', fn($q3) =>
                      $q3->where('nama_ruangan', 'like', "%{$search}%")
                        ->orWhere('nama_gedung', 'like', "%{$search}%")
                  );
            });
        }

        // Paginasi (15 per halaman), pertahankan query string
        $laporanList = $query->paginate(15)->withQueryString();

        // ── Hitung total per status untuk badge ──────────────────────
        $statTotal    = Laporan::count();
        $statProses   = Laporan::where('Status_terkini', 'Sedang Diperbaiki')->count();
        $statSelesai  = Laporan::where('Status_terkini', 'Selesai')->count();

        // ── Pre-build JSON untuk modal JavaScript ────────────────────
        $laporanJson = $laporanList->getCollection()->map(function ($lap) {
            $lokasi    = $lap->lokasi;
            $lokasiStr = collect([
                $lokasi?->nama_gedung,
                $lokasi?->nama_ruangan,
            ])->filter()->implode(', ') ?: '–';

            $foto = null;
            if ($lap->foto) {
                $foto = str_starts_with($lap->foto, 'http') || str_starts_with($lap->foto, '//')
                    ? $lap->foto
                    : asset('storage/' . $lap->foto);
            }

            return [
                'id'       => $lap->id_laporan,
                'pelapor'  => $lap->mahasiswa?->Nama_mahasiswa ?? 'Anonim',
                'nim'      => $lap->mahasiswa?->Nim ?? '–',
                'deskripsi'=> $lap->deskripsi ?? '–',
                'lokasi'   => $lokasiStr,
                'kategori' => $lap->kategori?->nama_kategori ?? '–',
                'tingkat'  => $lap->Tingkat_Kerusakan,
                'status'   => $lap->Status_terkini,
                'foto'     => $foto,
                'tanggal'  => $lap->created_at?->format('d M Y, H:i') ?? '–',
                'updated'  => $lap->updated_at?->format('d M Y, H:i') ?? '–',
            ];
        })->values();

        return view('admin.riwayat', compact(
            'user', 'admin',
            'laporanList',
            'statTotal', 'statProses', 'statSelesai',
            'laporanJson',
        ));
    }
}

