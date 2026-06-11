<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_mahasiswa',
        'id_kategori',
        'id_lokasi',
        'deskripsi',
        'foto',
        'Tingkat_Kerusakan',
        'Status_terkini',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriFasilitas::class, 'id_kategori', 'id_kategori');
    }

    public function lokasi()
    {
        return $this->belongsTo(LokasiFasilitas::class, 'id_lokasi', 'id_lokasi');
    }
}
