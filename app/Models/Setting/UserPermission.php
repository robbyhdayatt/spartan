<?php
namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;
    protected $table = 'user_permission';
    protected $primaryKey = 'id_permission';
    public $timestamps = false; // Tabel ini tidak punya kolom timestamps

    protected $fillable = ['id_user', 'module_name', 'can_create', 'can_read', 'can_update', 'can_delete'];
}