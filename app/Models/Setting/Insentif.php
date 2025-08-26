<?php
namespace App\Models\Setting;

use App\Models\Master\Jabatan;
use App\Models\Master\Part;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insentif extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'insentif';
    protected $primaryKey = 'id_insentif';
    protected $fillable = ['nama_program', 'id_part', 'id_jabatan', 'tipe_insentif', 'nilai_insentif', 'minimum_target', 'periode_awal', 'periode_akhir', 'status_aktif', 'created_by'];

    public function part() { return $this->belongsTo(Part::class, 'id_part'); }
    public function jabatan() { return $this->belongsTo(Jabatan::class, 'id_jabatan'); }
}