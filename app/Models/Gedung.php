<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    protected $table = 'gedungs';

    protected $fillable = [
        'nama_gedung',
        'kode_gedung',
        'deskripsi',
    ];

    public function lantais()
    {
        return $this->hasMany(Lantai::class, 'id_gedung');
    }
}
