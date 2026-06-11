<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('aktivitas')) {
            $query->where('aktivitas', 'like', '%' . $request->aktivitas . '%');
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $logs = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('superadmin.audit-log.index', compact('logs'));
    }

    /**
     * Export log ke CSV.
     */
    public function export(Request $request)
    {
        $logs = AuditLog::with('user')
            ->orderByDesc('created_at')
            ->get();

        $filename = 'audit_log_' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'User', 'Aktivitas', 'Keterangan', 'IP Address', 'Waktu']);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->user?->username ?? 'System',
                    $log->aktivitas,
                    $log->keterangan,
                    $log->ip_address,
                    $log->created_at?->format('d/m/Y H:i:s'),
                ]);
            }
            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }
}
