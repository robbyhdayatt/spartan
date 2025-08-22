<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Master\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil 2 karyawan pertama untuk dijadikan user
        $adminKaryawan = Karyawan::first();
        $staffKaryawan = Karyawan::skip(1)->first();

        // Pastikan karyawan ada sebelum membuat user
        if ($adminKaryawan) {
            User::create([
                'id_karyawan' => $adminKaryawan->id_karyawan,
                'username' => 'admin',
                'password_hash' => Hash::make('password'),
                'role_level' => 'admin',
                'status_aktif' => 1,
            ]);
        }

        if ($staffKaryawan) {
            User::create([
                'id_karyawan' => $staffKaryawan->id_karyawan,
                'username' => 'staff',
                'password_hash' => Hash::make('password'),
                'role_level' => 'staff',
                'status_aktif' => 1,
            ]);
        }
    }
}
