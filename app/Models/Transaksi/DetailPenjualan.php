<?php

namespace App\Models\Transaksi;

use App\Models\Master\Part;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';
    protected $primaryKey = 'id_detail_penjualan';

    protected $fillable = [
        'id_penjualan', 'id_part', 'quantity', 'harga_satuan', 'subtotal',
        'diskon_persen', 'diskon_rupiah', 'total_after_diskon',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class, 'id_part');
    }
}