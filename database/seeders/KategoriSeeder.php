<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategories = [
            ['nama' => 'Makanan', 'deskripsi' => 'Makanan berat dan ringan'],
            ['nama' => 'Minuman', 'deskripsi' => 'Minuman kemasan dan segar'],
            ['nama' => 'Snack', 'deskripsi' => 'Camilan dan kue'],
            ['nama' => 'Sembako', 'deskripsi' => 'Bahan pokok kebutuhan sehari-hari'],
            ['nama' => 'ATK', 'deskripsi' => 'Alat tulis kantor'],
        ];

        foreach ($kategories as $kategori) {
            Kategori::create($kategori);
        }
    }
}