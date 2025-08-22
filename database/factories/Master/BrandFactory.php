<?php
namespace Database\Factories\Master;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    public function definition()
    {
        $brands = [
            ['nama' => 'Honda Genuine Parts', 'negara' => 'Jepang'],
            ['nama' => 'Yamaha Genuine Parts', 'negara' => 'Jepang'],
            ['nama' => 'Suzuki Genuine Parts', 'negara' => 'Jepang'],
            ['nama' => 'Kawasaki Genuine Parts', 'negara' => 'Jepang'],
            ['nama' => 'Aspira', 'negara' => 'Indonesia'],
            ['nama' => 'Federal Parts', 'negara' => 'Indonesia'],
            ['nama' => 'NGK', 'negara' => 'Jepang'],
            ['nama' => 'Denso', 'negara' => 'Jepang'],
            ['nama' => 'Bosch', 'negara' => 'Jerman'],
            ['nama' => 'Brembo', 'negara' => 'Italia'],
        ];

        $brand = $this->faker->unique()->randomElement($brands);

        return [
            'nama_brand' => $brand['nama'],
            'negara_asal' => $brand['negara'],
            'status_aktif' => 1,
        ];
    }
}
