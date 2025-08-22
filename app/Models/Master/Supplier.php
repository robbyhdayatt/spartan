<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes

class Supplier extends Model
{
    use HasFactory, SoftDeletes; // Gunakan SoftDeletes

    protected $table = 'supplier'; // Nama tabel sesuai SQL Anda
    protected $primaryKey = 'id_supplier'; // Primary key sesuai SQL Anda [cite: 58]

    // Kolom-kolom yang boleh diisi secara massal
    // Disesuaikan dengan semua kolom di tabel 'supplier' Anda
    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'telepon',
        'email',
        'contact_person',
        'npwp',
        'rating_supplier',
        'status_aktif',
    ];

    /**
     * Laravel akan otomatis menangani kolom `created_at` dan `updated_at`.
     * Kita menggunakan SoftDeletes untuk `deleted_at`[cite: 73].
     */
}
