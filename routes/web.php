<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

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

    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');

    // TRANSAKSI
    Route::prefix('transaksi')->group(function() {
        // Pembelian
        Route::get('pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
        Route::post('pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
        Route::get('pembelian/{pembelian}/details', [PembelianController::class, 'getDetailsJson'])->name('pembelian.details.json');
        Route::post('pembelian/{pembelian}/submit-approval', [PembelianController::class, 'submitApproval'])->name('pembelian.submit');
        Route::post('pembelian/{pembelian}/approve', [PembelianController::class, 'approve'])->name('pembelian.approve');
        Route::post('pembelian/{pembelian}/reject', [PembelianController::class, 'reject'])->name('pembelian.reject');

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

        // Stock Adjustment
        Route::get('adjustment', [StockAdjustmentController::class, 'index'])->name('adjustment.index');
        Route::post('adjustment', [StockAdjustmentController::class, 'store'])->name('adjustment.store');
        Route::get('get-stock-sistem', [StockAdjustmentController::class, 'getStockSistem'])->name('adjustment.get-stock');
        
        // Retur
        Route::get('retur', [ReturController::class, 'index'])->name('retur.index');
        Route::post('retur', [ReturController::class, 'store'])->name('retur.store');
        Route::get('get-items-for-return', [ReturController::class, 'getItemsForReturn'])->name('retur.get-items');
    });

    // LAPORAN
    Route::prefix('laporan')->group(function() {
        Route::get('stok', [StokController::class, 'index'])->name('laporan.stok.index');
    });

    // PENGATURAN / SETTING
    Route::prefix('settings')->group(function() {
        Route::resource('approval-levels', ApprovalLevelController::class)->parameters(['approval-levels' => 'approvalLevel']);
        Route::resource('users', UserController::class);
    });
});