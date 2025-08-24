<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales';
    protected $primaryKey = 'id_sales';

    protected $fillable = [
        'id_karyawan',
        'id_konsumen',
        'tanggal_assign',
        'target_penjualan',
        'status_aktif',
    ];

    /**
     * Relasi ke model Karyawan.
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    /**
     * Relasi ke model Konsumen.
     */
    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen');
    }
}