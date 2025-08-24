<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokLokasi extends Model
{
    use HasFactory;

    protected $table = 'stok_lokasi';
    protected $primaryKey = 'id_stok_lokasi';

    // Tabel ini tidak menggunakan created_at dan updated_at standar Laravel
    public $timestamps = false;

    protected $fillable = [
        'id_part',
        'id_gudang',
        'quantity',
        'quantity_rusak',
        'quantity_quarantine',
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
}