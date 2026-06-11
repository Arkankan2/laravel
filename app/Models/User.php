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
        'nama',
        'email',
        'status',
        'foto_profil',
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

    /**
     * Cek apakah akun aktif.
     */
    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }

    /**
     * Relasi ke audit logs.
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id', 'id_user');
    }

    /**
     * Scope untuk filter per role.
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}
