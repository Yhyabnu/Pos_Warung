<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => $this->faker->randomElement(['Makanan', 'Minuman', 'Snack', 'Sembako', 'ATK']),
            'deskripsi' => $this->faker->sentence(),
            'aktif' => true,
        ];
    }
}