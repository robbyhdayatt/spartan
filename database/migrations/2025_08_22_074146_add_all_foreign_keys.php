<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawan')->onDelete('set null');
        });

        Schema::table('gudang', function (Blueprint $table) {
            $table->foreign('id_pic_gudang')->references('id_karyawan')->on('karyawan')->onDelete('set null');
        });

        Schema::table('karyawan', function (Blueprint $table) {
            $table->foreign('id_jabatan')->references('id_jabatan')->on('jabatan')->onDelete('cascade');
            $table->foreign('id_gudang_asal')->references('id_gudang')->on('gudang')->onDelete('set null');
        });

        Schema::table('part', function (Blueprint $table) {
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('set null');
            $table->foreign('id_brand')->references('id_brand')->on('brand')->onDelete('set null');
        });

        Schema::table('pembelian', function (Blueprint $table) {
            $table->foreign('id_supplier')->references('id_supplier')->on('supplier')->onDelete('set null');
        });

        Schema::table('detail_pembelian', function (Blueprint $table) {
            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelian')->onDelete('cascade');
            $table->foreign('id_part')->references('id_part')->on('part')->onDelete('cascade');
        });

        Schema::table('penjualan', function (Blueprint $table) {
            $table->foreign('id_konsumen')->references('id_konsumen')->on('konsumen')->onDelete('set null');
            $table->foreign('id_sales')->references('id_karyawan')->on('karyawan')->onDelete('set null');
        });

        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->foreign('id_penjualan')->references('id_penjualan')->on('penjualan')->onDelete('cascade');
            $table->foreign('id_part')->references('id_part')->on('part')->onDelete('cascade');
        });

        Schema::table('penerimaan', function (Blueprint $table) {
            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelian')->onDelete('set null');
            $table->foreign('id_gudang_tujuan')->references('id_gudang')->on('gudang')->onDelete('set null');
        });

        Schema::table('detail_penerimaan', function (Blueprint $table) {
            $table->foreign('id_penerimaan')->references('id_penerimaan')->on('penerimaan')->onDelete('cascade');
            $table->foreign('id_detail_pembelian')->references('id_detail_pembelian')->on('detail_pembelian')->onDelete('set null');
        });

        Schema::table('stok_summary', function (Blueprint $table) {
            $table->foreign('id_part')->references('id_part')->on('part')->onDelete('cascade');
        });

        Schema::table('stok_lokasi', function (Blueprint $table) {
            $table->foreign('id_part')->references('id_part')->on('part')->onDelete('cascade');
            $table->foreign('id_gudang')->references('id_gudang')->on('gudang')->onDelete('cascade');
        });

        // Anda bisa menambahkan foreign key lainnya di sini jika diperlukan
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop foreign keys in reverse order of creation
        Schema::table('stok_lokasi', function (Blueprint $table) {
            $table->dropForeign(['id_part']);
            $table->dropForeign(['id_gudang']);
        });
        Schema::table('stok_summary', function (Blueprint $table) { $table->dropForeign(['id_part']); });
        Schema::table('detail_penerimaan', function (Blueprint $table) {
            $table->dropForeign(['id_penerimaan']);
            $table->dropForeign(['id_detail_pembelian']);
        });
        Schema::table('penerimaan', function (Blueprint $table) {
            $table->dropForeign(['id_pembelian']);
            $table->dropForeign(['id_gudang_tujuan']);
        });
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->dropForeign(['id_penjualan']);
            $table->dropForeign(['id_part']);
        });
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropForeign(['id_konsumen']);
            $table->dropForeign(['id_sales']);
        });
        Schema::table('detail_pembelian', function (Blueprint $table) {
            $table->dropForeign(['id_pembelian']);
            $table->dropForeign(['id_part']);
        });
        Schema::table('pembelian', function (Blueprint $table) { $table->dropForeign(['id_supplier']); });
        Schema::table('part', function (Blueprint $table) {
            $table->dropForeign(['id_kategori']);
            $table->dropForeign(['id_brand']);
        });
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropForeign(['id_jabatan']);
            $table->dropForeign(['id_gudang_asal']);
        });
        Schema::table('gudang', function (Blueprint $table) { $table->dropForeign(['id_pic_gudang']); });
        Schema::table('user', function (Blueprint $table) { $table->dropForeign(['id_karyawan']); });
    }
}
