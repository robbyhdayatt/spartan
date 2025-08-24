<?php

namespace App\Models\Transaksi;

use App\Models\Master\Konsumen;
use App\Models\Master\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Retur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'retur';
    protected $primaryKey = 'id_retur';

    protected $fillable = [
        'nomor_retur', 'tipe_retur', 'id_konsumen', 'id_supplier',
        'id_penjualan', 'id_pembelian', 'tanggal_retur', 'alasan',
        'total_amount', 'status_retur', 'created_by'
    ];

    public function konsumen() { return $this->belongsTo(Konsumen::class, 'id_konsumen'); }
    public function supplier() { return $this->belongsTo(Supplier::class, 'id_supplier'); }
    public function penjualan() { return $this->belongsTo(Penjualan::class, 'id_penjualan'); }
    public function pembelian() { return $this->belongsTo(Pembelian::class, 'id_pembelian'); }
    public function details() { return $this->hasMany(DetailRetur::class, 'id_retur'); }
}