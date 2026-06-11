<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lantai extends Model
{
    protected $table = 'lantais';

    protected $fillable = [
        'id_gedung',
        'nama_lantai',
        'nomor_lantai',
    ];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'id_gedung');
    }

    public function ruangans()
    {
        return $this->hasMany(Ruangan::class, 'id_lantai');
    }
}
