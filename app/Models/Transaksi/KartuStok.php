<?php

namespace App\Models\Transaksi;

use App\Models\Master\Gudang;
use App\Models\Master\Part;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuStok extends Model
{
    use HasFactory;

    protected $table = 'kartu_stok';
    protected $primaryKey = 'id_kartu_stok';

    // Beritahu Laravel bahwa tabel ini hanya punya created_at, tidak ada updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'id_part',
        'id_gudang',
        'tanggal_transaksi',
        'jenis_transaksi',
        'referensi_dokumen',
        'referensi_id',
        'nomor_dokumen',
        'masuk',
        'keluar',
        'saldo',
        'harga_satuan',
        'nilai_transaksi',
        'batch_number',
        'expired_date',
        'kondisi_stok',
        'keterangan',
        'created_by',
    ];

    /**
     * Relasi ke model Part.
     */
    public function part()
    {
        return $this->belongsTo(Part::class, 'id_part');
    }

    /**
     * Relasi ke model Gudang.
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'id_gudang');
    }

    /**
     * Relasi ke model User (untuk created_by).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}