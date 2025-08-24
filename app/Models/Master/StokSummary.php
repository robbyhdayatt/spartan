<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokSummary extends Model
{
    use HasFactory;
    
    protected $table = 'stok_summary';
    protected $primaryKey = 'id_part'; // Primary key bukan auto-increment

    public $incrementing = false; // Beritahu Laravel bahwa PK bukan integer auto-increment
    public $timestamps = false; // Tabel ini tidak menggunakan created_at/updated_at

    protected $fillable = [
        'id_part',
        'stok_tersedia',
        'stok_rusak',
        'stok_quarantine',
        'stok_total',
        'last_updated'
    ];
}