<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Master\Konsumen;

class KonsumenSeeder extends Seeder
{
    public function run()
    {
        Konsumen::factory(25)->create(); // Membuat 25 data Konsumen
    }
}
