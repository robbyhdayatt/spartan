<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Master\Part;

class PartSeeder extends Seeder
{
    public function run()
    {
        Part::factory(100)->create(); // Membuat 100 data Part
    }
}
