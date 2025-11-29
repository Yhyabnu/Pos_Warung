<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Kategori;

class ProdukFactory extends Factory
{
    public function definition(): array
    {
        $kategori = Kategori::inRandomOrder()->first() ?? Kategori::factory()->create();
        
        return [
            'kategori_id' => $kategori->id,
            'kode_barang' => $this->generateKodeBarang($kategori->nama),
            'nama' => $this->faker->words(2, true),
            'deskripsi' => $this->faker->sentence(),
            'harga_beli' => $this->faker->numberBetween(1000, 10000),
            'harga_jual' => $this->faker->numberBetween(2000, 15000),
            'stok' => $this->faker->numberBetween(10, 100),
            'stok_minimum' => 5,
            'satuan' => $this->faker->randomElement(['pcs', 'bungkus', 'kg', 'liter']),
            'gambar' => null,
            'aktif' => true,
        ];
    }

    private function generateKodeBarang($kategori): string
    {
        $prefix = match($kategori) {
            'Makanan' => 'MKN',
            'Minuman' => 'MNM', 
            'Snack' => 'SNK',
            'Sembako' => 'SMB',
            'ATK' => 'ATK',
            default => 'PRD'
        };

        return $prefix . '-' . str_pad($this->faker->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);
    }
}