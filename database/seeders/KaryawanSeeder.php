<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Master\Karyawan;

class KaryawanSeeder extends Seeder
{
    public function run()
    {
        Karyawan::factory(20)->create(); // Membuat 20 data Karyawan
    }
}
