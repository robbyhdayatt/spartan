<?php
namespace App\Models\Setting;

use App\Models\Master\Jabatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalLevel extends Model
{
    use HasFactory;
    protected $table = 'approval_level';
    protected $primaryKey = 'id_approval_level';
    protected $fillable = ['jenis_dokumen', 'level_sequence', 'nama_level', 'minimum_amount', 'id_jabatan_required', 'is_mandatory', 'status_aktif'];

    public function jabatan() { return $this->belongsTo(Jabatan::class, 'id_jabatan_required'); }
}