<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Master\Gudang;

class GudangSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('gudang')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        Gudang::create(['kode_gudang' => 'GD-PST', 'nama_gudang' => 'Gudang Pusat - Bandar Lampung', 'jenis_gudang' => 'utama']);
        Gudang::create(['kode_gudang' => 'GD-MTR', 'nama_gudang' => 'Gudang Cabang - Metro', 'jenis_gudang' => 'transit']);
        Gudang::create(['kode_gudang' => 'GD-PSW', 'nama_gudang' => 'Gudang Cabang - Pringsewu', 'jenis_gudang' => 'transit']);
        Gudang::create(['kode_gudang' => 'GD-RTN', 'nama_gudang' => 'Gudang Retur Pusat', 'jenis_gudang' => 'retur']);
        Gudang::create(['kode_gudang' => 'GD-QCC', 'nama_gudang' => 'Gudang Karantina QC', 'jenis_gudang' => 'quarantine']);
    }
}