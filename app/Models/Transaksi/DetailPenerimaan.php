<?php

namespace App\Models\Transaksi;

use App\Models\Master\Part;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenerimaan extends Model
{
    use HasFactory;

    protected $table = 'detail_penerimaan';
    protected $primaryKey = 'id_detail_penerimaan';

    protected $fillable = [
        'id_penerimaan', 'id_detail_pembelian', 'id_part', 'qty_dipesan',
        'qty_diterima', 'kondisi_barang', 'qc_notes', 'batch_number', 'expired_date'
    ];

    public function part()
    {
        return $this->belongsTo(Part::class, 'id_part');
    }
}
