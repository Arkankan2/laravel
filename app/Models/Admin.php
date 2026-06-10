<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'nip',
        'nama_admin',
        'sandi',
        'kontak',
    ];

    protected $hidden = ['sandi'];
}
