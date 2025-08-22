<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jabatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jabatan'; // Sesuai rancangan database Anda
    protected $primaryKey = 'id_jabatan'; // Sesuai rancangan database Anda

    protected $fillable = [
        'nama_jabatan',
        'deskripsi',
        'level_jabatan',
        'status_aktif',
    ];
}
