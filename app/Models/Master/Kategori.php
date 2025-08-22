<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori'; // Sesuai rancangan database Anda
    protected $primaryKey = 'id_kategori'; // Sesuai rancangan database Anda

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'parent_kategori', // Untuk relasi parent-child
        'status_aktif',
    ];

    // Definisikan relasi ke parent-nya sendiri
    public function parent()
    {
        return $this->belongsTo(Kategori::class, 'parent_kategori');
    }
}
