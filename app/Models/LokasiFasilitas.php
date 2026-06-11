<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiFasilitas extends Model
{
    protected $table = 'lokasi_fasilitas';
    protected $primaryKey = 'id_lokasi';

    protected $fillable = ['nama_ruangan', 'nama_gedung', 'Ruangan'];
}
