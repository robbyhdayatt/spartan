<?php

namespace App\Models\Transaksi;

use App\Models\Master\Part;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian';
    protected $primaryKey = 'id_detail_pembelian';

    // Nonaktifkan timestamps bawaan Laravel jika tabel tidak memilikinya
    // public $timestamps = false;

    protected $fillable = [
        'id_pembelian', 'id_part', 'quantity', 'harga_satuan', 'subtotal', 'keterangan'
    ];

    public function part()
    {
        return $this->belongsTo(Part::class, 'id_part');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }
}
