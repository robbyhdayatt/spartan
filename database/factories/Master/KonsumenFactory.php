<?php
namespace Database\Factories\Master;
use Illuminate\Database\Eloquent\Factories\Factory;

class KonsumenFactory extends Factory
{
    public function definition()
    {
        $prefix = $this->faker->randomElement(['Bengkel', 'Toko Onderdil', 'Service Center', 'CV.', 'PT.']);
        $suffix = $this->faker->randomElement(['Maju Jaya', 'Motor Sejahtera', 'Abadi', 'Bersama', 'Mandiri']);

        return [
            'kode_konsumen' => 'KSM-' . $this->faker->unique()->randomNumber(4),
            'nama_konsumen' => "$prefix $suffix",
            'alamat' => $this->faker->address,
            'telepon' => '08' . $this->faker->numerify('##########'),
            'limit_kredit' => $this->faker->randomElement([10000000, 25000000, 50000000]),
            'term_pembayaran' => $this->faker->randomElement([30, 60, 90]),
            'status_aktif' => 1,
        ];
    }
}
