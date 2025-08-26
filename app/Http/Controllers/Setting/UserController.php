<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Master\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('access', ['users', 'read']);
        $users = User::with('karyawan.jabatan')->latest()->paginate(10);
        
        // Ambil hanya karyawan yang belum punya akun user
        $karyawans = Karyawan::whereDoesntHave('user')->where('status_aktif', 1)->get();

        return view('setting.user.index', compact('users', 'karyawans'));
    }

    public function store(Request $request)
    {
        $this->authorize('access', ['users', 'create']);
        $request->validate([
            'id_karyawan' => 'required|integer|exists:karyawan,id_karyawan|unique:user,id_karyawan',
            'username' => 'required|string|max:100|unique:user,username',
            'role_level' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'id_karyawan' => $request->id_karyawan,
            'username' => $request->username,
            'role_level' => $request->role_level,
            'password_hash' => Hash::make($request->password),
            'status_aktif' => 1,
        ]);

        return redirect()->route('users.index')->with('success', 'User baru berhasil dibuat.');
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('access', ['users', 'update']);
        $request->validate([
            'username' => ['required', 'string', 'max:100', Rule::unique('user')->ignore($user->id_user, 'id_user')],
            'role_level' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->username = $request->username;
        $user->role_level = $request->role_level;

        if ($request->filled('password')) {
            $user->password_hash = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
       $this->authorize('access', ['users', 'delete']);
        // Sebaiknya jangan hapus user admin utama
        if ($user->id_user === 1) {
            return redirect()->route('users.index')->with('error', 'User admin utama tidak boleh dihapus.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}