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
});
