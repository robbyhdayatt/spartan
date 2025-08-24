<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Master\Jabatan;

class JabatanSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel terlebih dahulu
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('jabatan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $jabatans = [
            // Level Managerial (Pusat)
            ['nama_jabatan' => 'Business Sparepart Manager', 'level_jabatan' => 1],
            ['nama_jabatan' => 'Service & Part Manager', 'level_jabatan' => 1],
            // Level Supervisor (Pusat)
            ['nama_jabatan' => 'Inventory Supervisor', 'level_jabatan' => 2],
            ['nama_jabatan' => 'IT Supervisor', 'level_jabatan' => 2],
            // Level Pimpinan Cabang
            ['nama_jabatan' => 'Kepala Cabang', 'level_jabatan' => 2],
            // Level Staff Operasional (Cabang)
            ['nama_jabatan' => 'Part Counter', 'level_jabatan' => 3],
            ['nama_jabatan' => 'Admin Gudang', 'level_jabatan' => 3],
            ['nama_jabatan' => 'Staff Quality Control', 'level_jabatan' => 3],
            ['nama_jabatan' => 'Koor Sales', 'level_jabatan' => 3],
            // Level Sales
            ['nama_jabatan' => 'Salesman', 'level_jabatan' => 4],
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::create($jabatan);
        }
    }
}