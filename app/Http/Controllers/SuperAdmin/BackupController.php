<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    use LogsActivity;

    /**
     * Direktori penyimpanan backup (storage/app/backups).
     */
    private string $backupDir = 'backups';

    public function index()
    {
        $backups = Backup::orderByDesc('created_at')->get();
        return view('superadmin.backup.index', compact('backups'));
    }

    /**
     * Buat backup database menggunakan mysqldump.
     */
    public function store(Request $request)
    {
        $dbHost     = config('database.connections.mysql.host', '127.0.0.1');
        $dbPort     = config('database.connections.mysql.port', '3306');
        $dbName     = config('database.connections.mysql.database');
        $dbUser     = config('database.connections.mysql.username');
        $dbPassword = config('database.connections.mysql.password');

        $namaFile  = 'backup_' . now()->format('Ymd_His') . '.sql';
        $localPath = storage_path('app/' . $this->backupDir . '/' . $namaFile);

        // Pastikan direktori ada
        if (!file_exists(dirname($localPath))) {
            mkdir(dirname($localPath), 0755, true);
        }

        // Bangun perintah mysqldump (XAMPP Windows)
        $passwdOpt = !empty($dbPassword) ? "--password=$dbPassword" : '';
        $command   = "\"C:\\xampp\\mysql\\bin\\mysqldump.exe\" --host=$dbHost --port=$dbPort --user=$dbUser $passwdOpt $dbName > \"$localPath\" 2>&1";

        exec($command, $output, $returnCode);

        if ($returnCode !== 0 || !file_exists($localPath) || filesize($localPath) === 0) {
            return back()->with('error', 'Gagal membuat backup. Pastikan mysqldump tersedia. Error: ' . implode(' ', $output));
        }

        $ukuranFile = filesize($localPath);

        $backup = Backup::create([
            'nama_file'   => $namaFile,
            'ukuran_file' => $ukuranFile,
        ]);

        $this->logActivity('Backup Database', "Backup berhasil dibuat: $namaFile (ukuran: " . number_format($ukuranFile / 1024, 2) . " KB)");

        return redirect()->route('superadmin.backup.index')
            ->with('success', "Backup $namaFile berhasil dibuat.");
    }

    /**
     * Download file backup.
     */
    public function download($id): StreamedResponse
    {
        $backup    = Backup::findOrFail($id);
        $localPath = storage_path('app/' . $this->backupDir . '/' . $backup->nama_file);

        if (!file_exists($localPath)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        $this->logActivity('Download Backup', "Mengunduh file backup: {$backup->nama_file}");

        return response()->streamDownload(function () use ($localPath) {
            readfile($localPath);
        }, $backup->nama_file, [
            'Content-Type'   => 'application/octet-stream',
            'Content-Length' => filesize($localPath),
        ]);
    }

    /**
     * Hapus file backup.
     */
    public function destroy($id)
    {
        $backup    = Backup::findOrFail($id);
        $localPath = storage_path('app/' . $this->backupDir . '/' . $backup->nama_file);
        $namaFile  = $backup->nama_file;

        if (file_exists($localPath)) {
            unlink($localPath);
        }

        $backup->delete();

        $this->logActivity('Hapus Backup', "Menghapus file backup: $namaFile");

        return redirect()->route('superadmin.backup.index')
            ->with('success', "Backup $namaFile berhasil dihapus.");
    }
}
