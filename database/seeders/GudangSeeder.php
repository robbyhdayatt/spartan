<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Master\Gudang;

class GudangSeeder extends Seeder
{
    public function run()
    {
        Gudang::factory(5)->create(); // Membuat 5 data Gudang
    }
}
