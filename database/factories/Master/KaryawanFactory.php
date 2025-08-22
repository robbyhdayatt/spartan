<?php
namespace Database\Factories\Master;
use App\Models\Master\Jabatan;
use App\Models\Master\Gudang;
use Illuminate\Database\Eloquent\Factories\Factory;

class KaryawanFactory extends Factory
{
    public function definition()
    {
        return [
            'kode_karyawan' => 'KAR-' . $this->faker->unique()->randomNumber(4),
            'nama_karyawan' => $this->faker->name,
            'id_jabatan' => Jabatan::inRandomOrder()->first()->id_jabatan,
            'id_gudang_asal' => Gudang::inRandomOrder()->first()->id_gudang,
            'telepon' => '08' . $this->faker->numerify('##########'),
            'email' => $this->faker->unique()->safeEmail,
            'tanggal_masuk' => $this->faker->date(),
            'status_aktif' => 1,
        ];
    }
}
