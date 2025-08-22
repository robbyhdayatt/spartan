<?php
namespace Database\Factories\Master;
use Illuminate\Database\Eloquent\Factories\Factory;

class GudangFactory extends Factory
{
    public function definition()
    {
        return [
            'kode_gudang' => 'GD-' . $this->faker->unique()->randomNumber(3),
            'nama_gudang' => 'Gudang ' . $this->faker->city,
            'alamat' => $this->faker->address,
            'jenis_gudang' => $this->faker->randomElement(['utama', 'transit', 'retur']),
            'status_aktif' => 1,
        ];
    }
}
