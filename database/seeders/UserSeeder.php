<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Master\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('user')->truncate();

        // Ambil karyawan berdasarkan kode
        $manager = Karyawan::where('kode_karyawan', 'KAR-001')->first();
        $partCounter = Karyawan::where('kode_karyawan', 'KAR-004')->first();
        $itSupervisor = Karyawan::where('kode_karyawan', 'KAR-005')->first();
        $adminGudang = Karyawan::where('kode_karyawan', 'KAR-008')->first();
        $staffQC = Karyawan::where('kode_karyawan', 'KAR-009')->first();
        $supervisor = Karyawan::where('kode_karyawan', 'KAR-010')->first();
        $finance = Karyawan::where('kode_karyawan', 'KAR-011')->first();
        $kacab = Karyawan::where('kode_karyawan', 'KAR-012')->first();
        $ceo = Karyawan::where('kode_karyawan', 'KAR-013')->first();

        // Buat user untuk IT Supervisor sebagai Super Admin
        if ($itSupervisor) {
            User::create([
                'id_karyawan' => $itSupervisor->id_karyawan,
                'username' => 'admin.it',
                'password_hash' => Hash::make('password'),
                'role_level' => 'admin',
            ]);
        }
        
        // Buat user untuk Manager
        if ($manager) {
            User::create([
                'id_karyawan' => $manager->id_karyawan,
                'username' => 'budi.manager',
                'password_hash' => Hash::make('password'),
                'role_level' => 'manager',
            ]);
        }
        
        // Buat user untuk Staff
        if ($partCounter) {
            User::create([
                'id_karyawan' => $partCounter->id_karyawan,
                'username' => 'eka.staff',
                'password_hash' => Hash::make('password'),
                'role_level' => 'staff',
            ]);
        }
        // Buat user untuk Admin Gudang
        if ($adminGudang) {
            User::create([
                'id_karyawan' => $adminGudang->id_karyawan, 
                'username' => 'irfan.gudang', 
                'password_hash' => Hash::make('password'), 
                'role_level' => 'staff'
            ]);
        }
        // Buat user untuk Staff QC
        if ($staffQC) {
            User::create([
                'id_karyawan' => $staffQC->id_karyawan,
                'username' => 'joko.qc',
                'password_hash' => Hash::make('password'),
                'role_level' => 'staff'
            ]);
        }
        // TAMBAHKAN USER BARU UNTUK SUPERVISOR
        if ($supervisor) {
            User::create([
                'id_karyawan' => $supervisor->id_karyawan,
                'username' => 'siti.spv',
                'password_hash' => Hash::make('password'),
                'role_level' => 'supervisor'
            ]);
        }
        // TAMBAHKAN USER BARU UNTUK MANAGER
        if ($finance) {
            User::create(['id_karyawan' => $finance->id_karyawan, 
            'username' => 'rina.finance', 
            'password_hash' => Hash::make('password'), 
            'role_level' => 'manager'
            ]);
        }
        // TAMBAHKAN USER BARU UNTUK KEPALA CABANG
        if ($kacab) {
            User::create(['id_karyawan' => $kacab->id_karyawan, 'username' => 'agus.kacab', 'password_hash' => Hash::make('password'), 'role_level' => 'supervisor']);
        }
        // TAMBAHKAN USER BARU UNTUK CEO
        if ($ceo) {
            User::create(['id_karyawan' => $ceo->id_karyawan, 'username' => 'robby.ceo', 'password_hash' => Hash::make('password'), 'role_level' => 'viewer']);
        }
    }
}