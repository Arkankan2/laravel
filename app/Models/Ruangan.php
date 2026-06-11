<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangans';

    protected $fillable = [
        'id_lantai',
        'nama_ruangan',
        'kode_ruangan',
        'deskripsi',
    ];

    public function lantai()
    {
        return $this->belongsTo(Lantai::class, 'id_lantai');
    }

    public function gedung()
    {
        return $this->lantai?->gedung;
    }
}
