<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ... (semua use statement Anda sudah benar) ...
use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\Master\BrandController;
use App\Http\Controllers\Master\KategoriController;
use App\Http\Controllers\Master\JabatanController;
use App\Http\Controllers\Master\GudangController;
use App\Http\Controllers\Master\KaryawanController;
use App\Http\Controllers\Master\KonsumenController;
use App\Http\Controllers\Master\PartController;
use App\Http\Controllers\Transaksi\PembelianController;
use App\Http\Controllers\Transaksi\PenerimaanController;
use App\Http\Controllers\Transaksi\PenjualanController;
use App\Http\Controllers\Transaksi\StockAdjustmentController;
use App\Http\Controllers\Transaksi\ReturController;
use App\Http\Controllers\Laporan\StokController;
use App\Http\Controllers\Setting\ApprovalLevelController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\Setting\UserController;
use App\Http\Controllers\Setting\HargaJualController;
use App\Http\Controllers\Setting\UserPermissionController;
use App\Http\Controllers\Setting\InsentifController;
use App\Http\Controllers\Setting\CampaignController;
use App\Http\Controllers\Laporan\InsentifController as LaporanInsentifController;

// ROUTE AWAL UNTUK HALAMAN UTAMA
Route::get('/', function () {
    // Jika sudah login, arahkan ke home. Jika belum, arahkan ke login.
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('auth.login');
});

// Hanya route login dan logout yang kita butuhkan, register dinonaktifkan
Auth::routes(['register' => false]);

// Semua route di bawah ini hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // MASTER DATA
    Route::resource('suppliers', SupplierController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('categories', KategoriController::class)->parameters(['categories' => 'category']);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('gudang', GudangController::class);
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('konsumen', KonsumenController::class)->parameters(['konsumen' => 'konsuman']);
    Route::resource('parts', PartController::class);

    // APPROVAL INBOX
    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');

    // TRANSAKSI
    Route::prefix('transaksi')->group(function() {
        // Pembelian
        Route::get('pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
        Route::post('pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
        Route::get('pembelian/{pembelian}/details', [PembelianController::class, 'getDetailsJson'])->name('pembelian.details.json');
        Route::post('pembelian/{pembelian}/submit-approval', [PembelianController::class, 'submitApproval'])->name('pembelian.submit');
        Route::post('pembelian/{pembelian}/approve', [PembelianController::class, 'approve'])->name('pembelian.approve');

        // Penerimaan
        Route::get('penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index');
        Route::get('penerimaan/create', [PenerimaanController::class, 'create'])->name('penerimaan.create');
        Route::post('penerimaan', [PenerimaanController::class, 'store'])->name('penerimaan.store');
        Route::get('penerimaan/{penerimaan}/details', [PenerimaanController::class, 'getDetailsJson'])->name('penerimaan.details.json');
        Route::get('penerimaan/{penerimaan}/qc', [PenerimaanController::class, 'showQcForm'])->name('penerimaan.qc');
        Route::post('penerimaan/{penerimaan}/qc', [PenerimaanController::class, 'processQc'])->name('penerimaan.processQc');
        
        // Penjualan
        Route::get('penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::post('penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
        Route::get('penjualan/{penjualan}/details', [PenjualanController::class, 'getDetailsJson'])->name('penjualan.details.json');
        Route::get('penjualan/get-harga', [PenjualanController::class, 'getHargaPart'])->name('penjualan.get-harga');
        Route::post('penjualan/{penjualan}/mark-as-delivered', [PenjualanController::class, 'markAsDelivered'])->name('penjualan.delivered');
        Route::post('penjualan/{penjualan}/mark-as-completed', [PenjualanController::class, 'markAsCompleted'])->name('penjualan.completed');

        // Stock Adjustment
        Route::get('adjustment', [StockAdjustmentController::class, 'index'])->name('adjustment.index');
        Route::post('adjustment', [StockAdjustmentController::class, 'store'])->name('adjustment.store');
        Route::get('get-stock-sistem', [StockAdjustmentController::class, 'getStockSistem'])->name('adjustment.get-stock');
        Route::get('adjustment/{adjustment}/details', [StockAdjustmentController::class, 'getDetailsJson'])->name('adjustment.details.json');
        Route::post('adjustment/{adjustment}/submit', [StockAdjustmentController::class, 'submitApproval'])->name('adjustment.submit');
        Route::post('adjustment/{adjustment}/approve', [StockAdjustmentController::class, 'approve'])->name('adjustment.approve');        
        
        // Retur
        Route::get('retur', [ReturController::class, 'index'])->name('retur.index');
        Route::post('retur', [ReturController::class, 'store'])->name('retur.store');
        Route::get('get-items-for-return', [ReturController::class, 'getItemsForReturn'])->name('retur.get-items');
        Route::get('retur/{retur}/details', [ReturController::class, 'getDetailsJson'])->name('retur.details.json');
    });

    // LAPORAN
    Route::prefix('laporan')->group(function() {
        Route::get('stok', [StokController::class, 'index'])->name('laporan.stok.index');
        Route::get('insentif', [LaporanInsentifController::class, 'index'])->name('laporan.insentif.index');
        Route::post('insentif/hitung', [LaporanInsentifController::class, 'hitung'])->name('laporan.insentif.hitung');
        Route::post('insentif/{realisasi}/mark-as-paid', [LaporanInsentifController::class, 'markAsPaid'])->name('laporan.insentif.paid');
    });

    // PENGATURAN / SETTING
    Route::prefix('settings')->group(function() {
        Route::resource('approval-levels', ApprovalLevelController::class)->parameters(['approval-levels' => 'approvalLevel']);
        Route::resource('users', UserController::class);
        Route::resource('harga-jual', HargaJualController::class);
        Route::get('permissions', [UserPermissionController::class, 'index'])->name('permissions.index');
        Route::post('permissions', [UserPermissionController::class, 'update'])->name('permissions.update');
        Route::resource('insentif', InsentifController::class);
        Route::resource('campaign', CampaignController::class);
    });
});