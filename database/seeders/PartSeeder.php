<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Master\Part;

class PartSeeder extends Seeder
{
    public function run()
    {
        Part::factory(10)->create(); // Membuat 10 data Part
    }
}
