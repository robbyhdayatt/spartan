<?php
namespace Database\Factories\Master;
use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    public function definition()
    {
        return [
            'nama_kategori' => $this->faker->word,
            'deskripsi' => $this->faker->sentence,
            'status_aktif' => 1,
        ];
    }
}
