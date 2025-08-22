<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Konsumen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'konsumen'; // Sesuai rancangan database
    protected $primaryKey = 'id_konsumen'; // Sesuai rancangan database

    protected $fillable = [
        'kode_konsumen',
        'nama_konsumen',
        'alamat',
        'kabupaten',
        'kecamatan',
        'provinsi',
        'kode_pos',
        'telepon',
        'email',
        'contact_person',
        'npwp',
        'limit_kredit',
        'term_pembayaran',
        'status_aktif',
    ];
}
