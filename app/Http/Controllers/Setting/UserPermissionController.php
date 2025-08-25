<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\UserPermission;
use App\Models\User;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    // Daftar semua modul yang ingin kita atur hak aksesnya
    private $modules = [
        'suppliers', 'brands', 'categories', 'jabatan', 'gudang', 'karyawan', 'konsumen', 'parts',
        'pembelian', 'penerimaan', 'penjualan', 'adjustment', 'retur',
        'laporan.stok', 'settings.users', 'settings.approval-levels', 'settings.permissions'
    ];

    public function index()
    {
        $users = User::with('permissions')->where('role_level', '!=', 'admin')->get(); // Admin punya semua akses
        $permissions = [];
        foreach ($users as $user) {
            foreach ($this->modules as $module) {
                $permissions[$user->id_user][$module] = $user->permissions->where('module_name', $module)->first();
            }
        }

        return view('setting.permission.index', [
            'users' => $users,
            'modules' => $this->modules,
            'permissions' => $permissions
        ]);
    }

    public function update(Request $request)
    {
        // Hapus semua izin lama
        UserPermission::truncate();

        if ($request->has('permissions')) {
            foreach ($request->permissions as $userId => $modules) {
                foreach ($modules as $moduleName => $actions) {
                    UserPermission::create([
                        'id_user' => $userId,
                        'module_name' => $moduleName,
                        'can_read' => isset($actions['read']),
                        'can_create' => isset($actions['create']),
                        'can_update' => isset($actions['update']),
                        'can_delete' => isset($actions['delete']),
                    ]);
                }
            }
        }

        return redirect()->route('permissions.index')->with('success', 'Hak akses berhasil diperbarui.');
    }
}