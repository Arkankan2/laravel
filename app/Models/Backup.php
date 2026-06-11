<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $table = 'backups';

    public $timestamps = false;

    protected $fillable = [
        'nama_file',
        'ukuran_file',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Ukuran file dalam format yang mudah dibaca (KB/MB).
     */
    public function getUkuranFormatAttribute(): string
    {
        $bytes = $this->ukuran_file;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }
}
