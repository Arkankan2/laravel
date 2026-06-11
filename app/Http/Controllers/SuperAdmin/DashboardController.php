<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Laporan;
use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $user = Auth::user();

        // ── Statistik Pengguna ──────────────────────────────────────────
        $totalUser    = User::count();
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalAdmin   = User::where('role', 'admin')->count();

        // ── Statistik Laporan ───────────────────────────────────────────
        $totalLaporan   = Laporan::count();
        $laporanSelesai = Laporan::where('Status_terkini', 'Selesai')->count();
        $laporanProses  = Laporan::where('Status_terkini', 'Sedang Diperbaiki')->count();

        // ── Laporan per bulan (12 bulan terakhir) ───────────────────────
        $laporanPerBulan = Laporan::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('tahun', 'bulan')
            ->orderBy('bulan')
            ->get();

        // Format untuk chart: isi 0 untuk bulan yang tidak ada data
        $bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $chartData   = array_fill(0, 12, 0);
        foreach ($laporanPerBulan as $row) {
            $chartData[$row->bulan - 1] = $row->total;
        }

        // ── Log aktivitas ───────────────────────────────────────────────
        $recentLogs = AuditLog::with('user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('superadmin.dashboard', compact(
            'user',
            'totalUser',
            'totalMahasiswa',
            'totalAdmin',
            'totalLaporan',
            'laporanSelesai',
            'laporanProses',
            'bulanLabels',
            'chartData',
            'recentLogs',
        ));
    }
}
