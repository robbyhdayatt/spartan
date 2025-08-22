<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gudang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gudang'; // Sesuai rancangan database Anda
    protected $primaryKey = 'id_gudang'; // Sesuai rancangan database Anda

    protected $fillable = [
        'kode_gudang',
        'nama_gudang',
        'alamat',
        'kota',
        'provinsi',
        'id_pic_gudang',
        'telepon',
        'kapasitas_maksimal',
        'jenis_gudang',
        'status_aktif',
    ];

    // Definisikan relasi ke Karyawan sebagai PIC
    public function pic()
    {
        return $this->belongsTo(Karyawan::class, 'id_pic_gudang');
    }
}
