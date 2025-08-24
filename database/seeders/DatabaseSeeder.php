<?php

namespace Database\Seeders;

use App\Models\Master\Gudang;
use App\Models\Master\Karyawan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Urutan seeder harus memperhatikan relasi tabel
        // Tabel tanpa foreign key ke master lain dijalankan terlebih dahulu
        $this->call([
            BrandSeeder::class,
            JabatanSeeder::class,
            GudangSeeder::class, // Gudang dibuat dulu tanpa PIC
            KategoriSeeder::class,
            KonsumenSeeder::class,
            SupplierSeeder::class,
        ]);

        // Karyawan dibuat setelah Jabatan dan Gudang ada
        $this->call([
            KaryawanSeeder::class,
        ]);
        // User dibuat setelah Karyawan ada
        $this->call([
            UserSeeder::class,
            SalesSeeder::class,
            ApprovalLevelSeeder::class,
        ]);

        // Setelah Karyawan ada, kita update PIC Gudang
        $this->command->info('Updating Gudang PICs...');
        $karyawans = Karyawan::all();
        Gudang::all()->each(function ($gudang) use ($karyawans) {
            $gudang->id_pic_gudang = $karyawans->random()->id_karyawan;
            $gudang->save();
        });
        $this->command->info('Gudang PICs updated successfully.');

        // Part dibuat setelah Brand dan Kategori ada
        $this->call([
            PartSeeder::class,
        ]);
    }
}
