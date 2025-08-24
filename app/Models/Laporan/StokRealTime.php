<?php

namespace App\Models\Laporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokRealTime extends Model
{
    use HasFactory;

    // Beritahu model untuk menggunakan view, bukan tabel
    protected $table = 'v_stok_realtime';

    // Tentukan primary key dari view tersebut
    protected $primaryKey = 'id_part';
}