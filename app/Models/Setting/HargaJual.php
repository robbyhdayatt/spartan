<?php

namespace App\Models\Setting;

use App\Models\Master\Konsumen;
use App\Models\Master\Part;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HargaJual extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'harga_jual';
    protected $primaryKey = 'id_harga_jual';

    protected $fillable = [
        'id_part',
        'id_konsumen',
        'hed', // Harga Ecer Disarankan
        'periode_awal',
        'periode_akhir',
        'status_aktif',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class, 'id_part');
    }

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen');
    }
}