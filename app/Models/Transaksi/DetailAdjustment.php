<?php

namespace App\Models\Transaksi;

use App\Models\Master\Part;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAdjustment extends Model
{
    use HasFactory;

    protected $table = 'detail_adjustment';
    protected $primaryKey = 'id_detail_adjustment';

    protected $fillable = [
        'id_adjustment', 'id_part', 'stok_sistem', 'stok_fisik',
        'harga_satuan', 'kondisi_stok', 'keterangan'
    ];

    public function part()
    {
        return $this->belongsTo(Part::class, 'id_part');
    }
}