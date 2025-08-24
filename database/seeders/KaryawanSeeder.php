<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Master\Karyawan;
use App\Models\Master\Jabatan;
use App\Models\Master\Gudang;

class KaryawanSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('karyawan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil ID dari Jabatan dan Gudang yang sudah dibuat
        $jabatanManager = Jabatan::where('nama_jabatan', 'Business Sparepart Manager')->first();
        $jabatanKepalaCabang = Jabatan::where('nama_jabatan', 'Kepala Cabang')->first();
        $jabatanPartCounter = Jabatan::where('nama_jabatan', 'Part Counter')->first();
        $jabatanAdminGudang = Jabatan::where('nama_jabatan', 'Admin Gudang')->first();
        $jabatanIT = Jabatan::where('nama_jabatan', 'IT Supervisor')->first();
        $jabatanKoorSales = Jabatan::where('nama_jabatan', 'Koor Sales')->first();
        $jabatanSalesman = Jabatan::where('nama_jabatan', 'Salesman')->first();
        
        $gudangPusat = Gudang::where('kode_gudang', 'GD-PST')->first();
        $gudangMetro = Gudang::where('kode_gudang', 'GD-MTR')->first();
        
        // Buat data karyawan
        Karyawan::create(['kode_karyawan' => 'KAR-001', 'nama_karyawan' => 'Budi Santoso', 'id_jabatan' => $jabatanManager->id_jabatan, 'id_gudang_asal' => $gudangPusat->id_gudang, 'status_aktif' => 1]);
        Karyawan::create(['kode_karyawan' => 'KAR-002', 'nama_karyawan' => 'Citra Lestari', 'id_jabatan' => $jabatanAdminGudang->id_jabatan, 'id_gudang_asal' => $gudangPusat->id_gudang, 'status_aktif' => 1]);
        Karyawan::create(['kode_karyawan' => 'KAR-003', 'nama_karyawan' => 'Doni Saputra', 'id_jabatan' => $jabatanKepalaCabang->id_jabatan, 'id_gudang_asal' => $gudangMetro->id_gudang, 'status_aktif' => 1]);
        Karyawan::create(['kode_karyawan' => 'KAR-004', 'nama_karyawan' => 'Eka Putri', 'id_jabatan' => $jabatanPartCounter->id_jabatan, 'id_gudang_asal' => $gudangMetro->id_gudang, 'status_aktif' => 1]);
        Karyawan::create(['kode_karyawan' => 'KAR-005', 'nama_karyawan' => 'Robby', 'id_jabatan' => $jabatanIT->id_jabatan, 'id_gudang_asal' => $gudangPusat->id_gudang, 'status_aktif' => 1]);
        Karyawan::create(['kode_karyawan' => 'KAR-006', 'nama_karyawan' => 'Gunawan', 'id_jabatan' => $jabatanKoorSales->id_jabatan, 'id_gudang_asal' => $gudangPusat->id_gudang, 'status_aktif' => 1]);
        Karyawan::create(['kode_karyawan' => 'KAR-007', 'nama_karyawan' => 'Herlina', 'id_jabatan' => $jabatanSalesman->id_jabatan, 'id_gudang_asal' => $gudangMetro->id_gudang, 'status_aktif' => 1]);

    }
}