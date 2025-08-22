<?php

namespace Database\Factories\Master;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $prefix = $this->faker->randomElement(['PT.', 'CV.', 'Distributor', 'Toko']);
        $suffix = $this->faker->randomElement(['Suku Cadang Prima', 'Mitra Otomotif', 'Jaya Abadi', 'Harapan Bersama', 'Teknik Mandiri']);

        return [
            'kode_supplier' => 'SUP-' . $this->faker->unique()->randomNumber(4),
            'nama_supplier' => "$prefix $suffix",
            'alamat' => $this->faker->address,
            'kota' => $this->faker->city,
            'telepon' => '08' . $this->faker->numerify('##########'),
            'email' => $this->faker->unique()->companyEmail,
            'contact_person' => $this->faker->name,
            'rating_supplier' => $this->faker->randomElement(['A', 'B', 'C']),
            'status_aktif' => 1,
        ];
    }
}
