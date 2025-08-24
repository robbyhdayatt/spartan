<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Karyawan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'karyawan';
    protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'kode_karyawan',
        'nama_karyawan',
        'id_jabatan',
        'id_gudang_asal',
        'telepon',
        'email',
        'alamat',
        'tanggal_masuk',
        'tanggal_keluar',
        'status_aktif',
    ];
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'id_gudang_asal');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id_karyawan');
    }
}
