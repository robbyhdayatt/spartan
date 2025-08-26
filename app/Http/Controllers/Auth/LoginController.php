<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * HAPUS ATAU BERI KOMENTAR PADA BARIS DI BAWAH INI.
     * Properti ini akan mengesampingkan (override) method redirectTo() jika masih aktif.
     * Inilah penyebab utama masalah Anda.
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    /**
     * Method ini akan secara dinamis menentukan halaman tujuan setelah login.
     * Laravel akan menggunakan method ini karena $redirectTo sudah tidak aktif.
     */
    public function redirectTo()
    {
        $user = Auth::user();

        // Tentukan prioritas halaman tujuan berdasarkan hak akses
        if ($user->can('access', ['dashboard', 'read'])) {
            return '/home';
        }

        if ($user->can('access', ['approvals', 'read'])) {
            return '/approvals';
        }
        
        if ($user->can('access', ['penjualan', 'read'])) {
            return '/transaksi/penjualan';
        }

        if ($user->can('access', ['pembelian', 'read'])) {
            return '/transaksi/pembelian';
        }

        if ($user->can('access', ['penerimaan', 'read'])) {
            return '/transaksi/penerimaan';
        }
        
        if ($user->can('access', ['laporan.stok', 'read'])) {
            return '/laporan/stok';
        }
        
        // Jika tidak ada izin sama sekali, logout dan beri pesan error.
        Auth::logout();
        
        // Simpan pesan error di session sebelum redirect
        session()->flash('error', 'Akun Anda tidak memiliki hak akses untuk melihat halaman manapun.');
        
        return '/login';
    }
}