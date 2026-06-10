<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';

    protected $fillable = [
        'Nim',
        'Nama_mahasiswa',
        'Sandi',
        'Kontak',
        'jenis_kelamin',
        'prodi',
        'agama',
        'tanggal_lahir',
        'ukm',
        'foto_profil',
    ];

    protected $hidden = ['Sandi'];
}
