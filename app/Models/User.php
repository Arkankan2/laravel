<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Kolom yang dipakai sebagai primary key untuk session Auth.
     */
    public function getAuthIdentifierName(): string
    {
        return 'id_user';
    }

    /**
     * Kolom "username" untuk credential login.
     * Ini membuat Auth::attempt(['username' => ..., 'password' => ...]) bekerja.
     */
    public function getAuthPassword(): string
    {
        return $this->password;
    }
}
