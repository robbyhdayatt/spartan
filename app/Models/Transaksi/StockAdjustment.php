<?php

namespace App\Models\Transaksi;

use App\Models\Master\Gudang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $table = 'stock_adjustment';
    protected $primaryKey = 'id_adjustment';

    protected $fillable = [
        'nomor_adjustment', 'tanggal_adjustment', 'jenis_adjustment', 'id_gudang',
        'keterangan', 'status_adjustment', 'created_by', 'approved_by',
        'total_selisih_qty', 'total_selisih_value'
    ];

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'id_gudang');
    }

    public function details()
    {
        return $this->hasMany(DetailAdjustment::class, 'id_adjustment');
    }
}