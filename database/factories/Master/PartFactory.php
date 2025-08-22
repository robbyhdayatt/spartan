<?php
namespace Database\Factories\Master;
use App\Models\Master\Brand;
use App\Models\Master\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartFactory extends Factory
{
    public function definition()
    {
        $namaPart = [
            'Kampas Rem Depan', 'Kampas Rem Belakang', 'Busi Iridium', 'Oli Mesin MPX-1 0.8L',
            'Filter Udara', 'Aki Kering GTZ-5S', 'Rantai Roda 428H', 'Gir Depan 14T',
            'Lampu Depan LED', 'V-Belt Kit', 'Bohlam Sein', 'Koil Pengapian'
        ];

        $kategori = Kategori::inRandomOrder()->first();
        $brand = Brand::inRandomOrder()->first();

        return [
            'kode_part' => $this->faker->unique()->bothify('??######'), // ex: YH123456
            'nama_part' => $this->faker->randomElement($namaPart) . ' ' . $brand->nama_brand,
            'id_kategori' => $kategori->id_kategori,
            'id_brand' => $brand->id_brand,
            'satuan' => 'Pcs',
            'minimum_stok' => $this->faker->numberBetween(5, 20),
            'harga_pokok' => $this->faker->numberBetween(25000, 500000),
            'require_qc' => $this->faker->boolean,
            'status_aktif' => 1,
        ];
    }
}
