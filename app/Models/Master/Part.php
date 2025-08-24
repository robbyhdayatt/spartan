<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'part'; // Sesuai rancangan database
    protected $primaryKey = 'id_part'; // Sesuai rancangan database

    protected $fillable = [
        'kode_part',
        'nama_part',
        'id_kategori',
        'id_brand',
        'spesifikasi',
        'info_kemasan',
        'satuan',
        'berat',
        'minimum_stok',
        'harga_pokok',
        'gambar_part',
        'barcode',
        'require_qc',
        'shelf_life_days',
        'status_aktif',
    ];

    // Definisikan relasi ke Kategori dan Brand
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'id_brand');
    }
    public function stok_summary()
    {
        return $this->hasOne(\App\Models\Master\StokSummary::class, 'id_part');
    }
}
