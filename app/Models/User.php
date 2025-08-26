<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Master\Karyawan;
use App\Models\Setting\UserPermission;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'username', 'password_hash', 'id_karyawan', 'role_level', 'status_aktif',
    ];

    protected $hidden = [
        'password_hash', 'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }
    
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    public function getNameAttribute()
    {
        return $this->karyawan ? $this->karyawan->nama_karyawan : $this->username;
    }

    /**
     * Relasi ke tabel user_permission.
     * TAMBAHKAN METHOD INI.
     */
    public function permissions()
    {
        return $this->hasMany(UserPermission::class, 'id_user');
    }
    public function permissions()
    {
        return $this->hasMany(\App\Models\Setting\UserPermission::class, 'id_user');
    }
}

