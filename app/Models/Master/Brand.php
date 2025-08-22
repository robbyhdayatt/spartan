<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'brand';
    protected $primaryKey = 'id_brand';

    // Kolom yang boleh diisi
    protected $fillable = [
        'nama_brand',     // [cite: 8]
        'negara_asal',    // [cite: 9]
        'status_aktif',   // [cite: 10]
    ];
}
