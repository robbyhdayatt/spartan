<?php
namespace Database\Seeders;

use App\Models\Master\Jabatan;
use App\Models\Setting\ApprovalLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApprovalLevelSeeder extends Seeder
{
    public function run()
    {
        DB::table('approval_level')->truncate();

        $managerJabatan = Jabatan::where('nama_jabatan', 'Business Sparepart Manager')->first();
        $supervisorJabatan = Jabatan::where('nama_jabatan', 'Inventory Supervisor')->first();

        // Aturan 1: Untuk PO dengan nilai >= 0 (semua PO)
        if ($supervisorJabatan) {
            ApprovalLevel::create([
                'jenis_dokumen' => 'pembelian',
                'nama_level' => 'Persetujuan Supervisor Gudang',
                'id_jabatan_required' => $supervisorJabatan->id_jabatan,
                'level_sequence' => 1,
                'minimum_amount' => 0, // Berlaku mulai dari Rp 0
                'status_aktif' => 1,
            ]);
        }

        // Aturan 2: Untuk PO dengan nilai >= 10.000.000
        if ($managerJabatan) {
            ApprovalLevel::create([
                'jenis_dokumen' => 'pembelian',
                'nama_level' => 'Persetujuan Manajer Sparepart',
                'id_jabatan_required' => $managerJabatan->id_jabatan,
                'level_sequence' => 2,
                'minimum_amount' => 10000000, // Berlaku mulai dari Rp 10 Juta
                'status_aktif' => 1,
            ]);
        }
    }
}