<?php

namespace App\Models\Transaksi;

use App\Models\Master\Gudang;
use App\Models\Master\Supplier;
use App\Models\Transaksi\Pembelian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penerimaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penerimaan';
    protected $primaryKey = 'id_penerimaan';

    protected $fillable = [
        'nomor_penerimaan', 'id_pembelian', 'tanggal_penerimaan', 'id_supplier',
        'id_gudang_tujuan', 'nomor_surat_jalan', 'status_penerimaan', 'keterangan_penerimaan',
        'pic_penerima', 'created_by'
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'id_gudang_tujuan');
    }

    public function details()
    {
        return $this->hasMany(DetailPenerimaan::class, 'id_penerimaan');
    }
}
