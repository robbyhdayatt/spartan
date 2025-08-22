<?php
namespace Database\Factories\Master;
use Illuminate\Database\Eloquent\Factories\Factory;

class JabatanFactory extends Factory
{
    public function definition()
    {
        $jabatan = $this->faker->unique()->randomElement([
            'Kepala Gudang',
            'Admin Gudang',
            'Staff Quality Control',
            'Salesman',
            'Manajer Pembelian',
            'Staff Mekanik',
            'Kasir',
            'Direktur Operasional'
        ]);

        return [
            'nama_jabatan' => $jabatan,
            'level_jabatan' => $this->faker->numberBetween(1, 5),
            'status_aktif' => 1,
        ];
    }
}
