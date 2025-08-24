<?php

namespace App\Models\Transaksi;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    use HasFactory;
    protected $table = 'approval_history';
    protected $primaryKey = 'id_approval';

    // Hanya created_at yang di-handle otomatis, updated_at tidak ada di tabel ini
    const UPDATED_AT = null;

    protected $fillable = [
        'jenis_dokumen',
        'id_dokumen',
        'level_approval',
        'id_approver',
        'status_approval',
        'tanggal_approval',
        'keterangan',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'id_approver');
    }
}