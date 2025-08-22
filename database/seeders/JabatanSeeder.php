<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Master\Jabatan;

class JabatanSeeder extends Seeder
{
    public function run()
    {
        Jabatan::factory(8)->create(); // Membuat 8 data Jabatan
    }
}
