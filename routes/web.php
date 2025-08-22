<?php

use Illuminate\Support\Facades\Route;
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
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Master Data
    Route::resource('suppliers', SupplierController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('categories', KategoriController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('gudang', GudangController::class);
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('konsumen', KonsumenController::class);
    Route::resource('parts', PartController::class);
    // Tambahkan resource untuk master data lain di sini nanti

    // TRANSAKSI
    Route::prefix('transaksi')->group(function() {
        // Pembelian
        Route::get('pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
        Route::post('pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
        Route::get('pembelian/{pembelian}/details', [PembelianController::class, 'getDetailsJson'])->name('pembelian.details.json');

        // Penerimaan
        Route::get('penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index');
        Route::get('penerimaan/create', [PenerimaanController::class, 'create'])->name('penerimaan.create');
        Route::get('penerimaan/{penerimaan}/details', [PenerimaanController::class, 'getDetailsJson'])->name('penerimaan.details.json');
        Route::post('penerimaan', [PenerimaanController::class, 'store'])->name('penerimaan.store');

    });
});
