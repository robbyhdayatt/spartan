<?php

namespace App\Models\Transaksi;

use App\Models\Master\Part;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailRetur extends Model
{
    use HasFactory;

    protected $table = 'detail_retur';
    protected $primaryKey = 'id_detail_retur';

    protected $fillable = [
        'id_retur', 'id_part', 'quantity', 'harga_satuan', 'subtotal',
        'kondisi_barang', 'tindakan', 'keterangan'
    ];

    public function part() { return $this->belongsTo(Part::class, 'id_part'); }
}