<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Catat aktivitas ke tabel audit_logs.
     *
     * @param string $aktivitas   Nama aktivitas (contoh: 'Login', 'Hapus Pengguna')
     * @param string|null $keterangan  Detail tambahan
     * @param string|null $ipAddress   IP address (opsional, default dari request)
     */
    protected function logActivity(string $aktivitas, ?string $keterangan = null, ?string $ipAddress = null): void
    {
        try {
            AuditLog::create([
                'user_id'    => Auth::check() ? Auth::user()->id_user : null,
                'aktivitas'  => $aktivitas,
                'keterangan' => $keterangan,
                'ip_address' => $ipAddress ?? request()->ip(),
            ]);
        } catch (\Throwable $e) {
            // Jangan sampai error log menghentikan proses utama
            \Log::warning('Gagal mencatat audit log: ' . $e->getMessage());
        }
    }
}
