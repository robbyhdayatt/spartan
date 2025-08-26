<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Master\Konsumen;

class KonsumenSeeder extends Seeder
{
    public function run()
    {
        Konsumen::factory(5)->create(); // Membuat 5 data Konsumen
    }
}
