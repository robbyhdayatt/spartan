<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStokRealtimeView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Hapus view jika sudah ada sebelumnya untuk menghindari error
        DB::statement("DROP VIEW IF EXISTS v_stok_realtime");

        // Buat view baru sesuai dengan rancangan Anda
        DB::statement("
            CREATE VIEW v_stok_realtime AS
            SELECT
                p.id_part,
                p.kode_part,
                p.nama_part,
                COALESCE(ss.stok_tersedia, 0) as stok_tersedia,
                COALESCE(ss.stok_reserved, 0) as stok_reserved,
                COALESCE(ss.stok_rusak, 0) as stok_rusak,
                COALESCE(ss.stok_quarantine, 0) as stok_quarantine,
                COALESCE(ss.stok_total, 0) as stok_total,
                COALESCE(ss.nilai_stok, 0) as nilai_stok,
                COALESCE(ss.harga_rata_rata, 0) as harga_rata_rata,
                p.minimum_stok,
                CASE
                    WHEN COALESCE(ss.stok_tersedia, 0) <= 0 THEN 'OUT_OF_STOCK'
                    WHEN COALESCE(ss.stok_tersedia, 0) <= p.minimum_stok THEN 'LOW_STOCK'
                    ELSE 'OK'
                END as status_stok
            FROM
                part p
            LEFT JOIN stok_summary ss ON p.id_part = ss.id_part
            WHERE
                p.status_aktif = 1 AND p.deleted_at IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS v_stok_realtime");
    }
}