<?php
namespace App\Models\Transaksi;

use App\Models\Master\Karyawan;
use App\Models\Setting\Insentif;
use Illuminate\Database\Eloquent\Model;

class RealisasiInsentif extends Model
{
    protected $table = 'realisasi_insentif';
    protected $primaryKey = 'id_realisasi';
    protected $fillable = [ 'id_insentif', 'id_karyawan', 'periode_bulan', 'periode_tahun', 'realisasi_qty', 'realisasi_value', 'persentase_pencapaian', 'nilai_insentif', 'status_bayar' ];

    public function insentif() { return $this->belongsTo(Insentif::class, 'id_insentif'); }
    public function karyawan() { return $this->belongsTo(Karyawan::class, 'id_karyawan'); }
}