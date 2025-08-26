<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Master\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::factory(5)->create(); // Membuat 5 data supplier
    }
}
