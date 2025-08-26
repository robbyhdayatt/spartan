<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'campaign';
    protected $primaryKey = 'id_campaign';

    protected $fillable = [
        'kode_campaign',
        'nama_campaign',
        'jenis_campaign',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_aktif',
        'created_by',
    ];
}