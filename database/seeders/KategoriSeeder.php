<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Master\Kategori;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel terlebih dahulu
        DB::table('kategori')->delete();

        // Buat Kategori Induk
        $mesin = Kategori::create(['nama_kategori' => 'Mesin', 'status_aktif' => 1]);
        $kelistrikan = Kategori::create(['nama_kategori' => 'Kelistrikan', 'status_aktif' => 1]);
        $pengereman = Kategori::create(['nama_kategori' => 'Sistem Pengereman', 'status_aktif' => 1]);
        $oli = Kategori::create(['nama_kategori' => 'Oli dan Cairan', 'status_aktif' => 1]);

        // Buat Sub-kategori
        Kategori::create(['nama_kategori' => 'Busi', 'parent_kategori' => $mesin->id_kategori, 'status_aktif' => 1]);
        Kategori::create(['nama_kategori' => 'Piston Kit', 'parent_kategori' => $mesin->id_kategori, 'status_aktif' => 1]);
        Kategori::create(['nama_kategori' => 'Aki', 'parent_kategori' => $kelistrikan->id_kategori, 'status_aktif' => 1]);
        Kategori::create(['nama_kategori' => 'Lampu', 'parent_kategori' => $kelistrikan->id_kategori, 'status_aktif' => 1]);
        Kategori::create(['nama_kategori' => 'Kampas Rem', 'parent_kategori' => $pengereman->id_kategori, 'status_aktif' => 1]);
        Kategori::create(['nama_kategori' => 'Cakram', 'parent_kategori' => $pengereman->id_kategori, 'status_aktif' => 1]);
        Kategori::create(['nama_kategori' => 'Oli Mesin', 'parent_kategori' => $oli->id_kategori, 'status_aktif' => 1]);
        Kategori::create(['nama_kategori' => 'Air Radiator', 'parent_kategori' => $oli->id_kategori, 'status_aktif' => 1]);
    }
}
