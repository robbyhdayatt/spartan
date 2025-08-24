<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Master\Karyawan;
use App\Models\Master\Konsumen;
use App\Models\Master\Sales;
use Illuminate\Support\Facades\DB;

class SalesSeeder extends Seeder
{
    public function run()
    {
        DB::table('sales')->truncate();

        // Ambil karyawan dengan jabatan Salesman
        $salesKaryawan = Karyawan::whereHas('jabatan', function ($q) {
            $q->where('nama_jabatan', 'Salesman');
        })->first();

        // Ambil 5 konsumen pertama untuk di-assign ke sales tersebut
        $konsumens = Konsumen::take(5)->get();

        if ($salesKaryawan && $konsumens->isNotEmpty()) {
            foreach ($konsumens as $konsumen) {
                Sales::create([
                    'id_karyawan' => $salesKaryawan->id_karyawan,
                    'id_konsumen' => $konsumen->id_konsumen,
                    'tanggal_assign' => now(),
                    'status_aktif' => 1,
                ]);
            }
        }
    }
}