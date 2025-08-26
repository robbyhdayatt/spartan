<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\UserPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class UserPermissionController extends Controller
{
    private $modules = [
        'dashboard',
        'approvals',
        'suppliers', 'brands', 'categories', 'jabatan', 'gudang', 'karyawan', 'konsumen', 'parts',
        'pembelian', 'penerimaan', 'penjualan',
        'adjustment', 'retur',
        'laporan.stok',
        'settings.approval-levels', 'settings.users', 'settings.permissions', 'settings.harga-jual',
        'settings.insentif',
        'settings.campaign',
        'laporan.insentif'
    ];

    public function index()
    {
        $this->authorize('access', ['settings.permissions', 'read']);

        // Ambil semua user kecuali admin, karena admin punya semua akses
        $users = User::with('permissions')->where('role_level', '!=', 'admin')->get(); 
        
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
        $this->authorize('access', ['settings.permissions', 'update']);

        // Hapus semua izin lama untuk user yang ada di form
        $userIds = array_keys($request->permissions ?? []);
        UserPermission::whereIn('id_user', $userIds)->delete();

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