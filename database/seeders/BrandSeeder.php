<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Master\Brand;

class BrandSeeder extends Seeder
{
    public function run()
    {
        Brand::factory(10)->create(); // Membuat 10 data brand
    }
}
