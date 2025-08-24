<?php

namespace App\Models\Transaksi;

use App\Models\Master\Konsumen;
use App\Models\Master\Karyawan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penjualan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';

    protected $fillable = [
        'nomor_invoice', 'nomor_so', 'id_konsumen', 'id_sales', 'tanggal_penjualan',
        'tanggal_jatuh_tempo', 'subtotal', 'total_diskon', 'ppn_amount', 'total_amount',
        'jenis_penjualan', 'status_penjualan', 'status_pembayaran', 'keterangan', 'created_by',
    ];

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen');
    }

    public function sales()
    {
        return $this->belongsTo(Karyawan::class, 'id_sales');
    }

    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
    }
}