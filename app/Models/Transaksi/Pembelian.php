<?php

namespace App\Models\Transaksi;

use App\Models\Master\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembelian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembelian';
    protected $primaryKey = 'id_pembelian';

    protected $fillable = [
        'nomor_po', 'id_supplier', 'tanggal_pembelian', 'tanggal_jatuh_tempo',
        'status_pembelian', 'status_approval', 'keterangan', 'created_by', 'approved_by',
        'subtotal', 'ppn_amount', 'total_amount'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function details()
    {
        return $this->hasMany(DetailPembelian::class, 'id_pembelian');
    }
}
