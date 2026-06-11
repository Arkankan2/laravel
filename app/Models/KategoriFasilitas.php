<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriFasilitas extends Model
{
    protected $table = 'kategori_fasilitas';
    protected $primaryKey = 'id_kategori';

    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'id_kategori', 'id_kategori');
    }
}
