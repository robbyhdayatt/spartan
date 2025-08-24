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
    }
}