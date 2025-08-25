<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Master\Karyawan;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password_hash', 'id_karyawan',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Definisi relasi ke tabel karyawan.
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    /**
     * Override default 'name' attribute to get name from karyawan table.
     */
    public function getNameAttribute()
    {
        // Jika relasi karyawan ada, ambil nama_karyawan. Jika tidak, ambil username.
        return $this->karyawan ? $this->karyawan->nama_karyawan : $this->username;
    }
    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
    public function permissions()
    {
        return $this->hasMany(\App\Models\Setting\UserPermission::class, 'id_user');
    }
}
