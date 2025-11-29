<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Kategori;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        // Data produk real untuk warung
        $produks = [
            // Makanan
            ['kategori_id' => 1, 'kode_barang' => 'MKN-001', 'nama' => 'Indomie Goreng', 'harga_beli' => 2500, 'harga_jual' => 3500, 'stok' => 50, 'satuan' => 'pcs'],
            ['kategori_id' => 1, 'kode_barang' => 'MKN-002', 'nama' => 'Indomie Kuah', 'harga_beli' => 2500, 'harga_jual' => 3500, 'stok' => 45, 'satuan' => 'pcs'],
            ['kategori_id' => 1, 'kode_barang' => 'MKN-003', 'nama' => 'Mie Sedap Goreng', 'harga_beli' => 2400, 'harga_jual' => 3400, 'stok' => 30, 'satuan' => 'pcs'],
            
            // Minuman
            ['kategori_id' => 2, 'kode_barang' => 'MNM-001', 'nama' => 'Aqua Botol 600ml', 'harga_beli' => 3000, 'harga_jual' => 5000, 'stok' => 60, 'satuan' => 'pcs'],
            ['kategori_id' => 2, 'kode_barang' => 'MNM-002', 'nama' => 'Teh Botol Sosro', 'harga_beli' => 3500, 'harga_jual' => 6000, 'stok' => 40, 'satuan' => 'pcs'],
            ['kategori_id' => 2, 'kode_barang' => 'MNM-003', 'nama' => 'Coca Cola 330ml', 'harga_beli' => 4500, 'harga_jual' => 7000, 'stok' => 35, 'satuan' => 'pcs'],
            
            // Snack
            ['kategori_id' => 3, 'kode_barang' => 'SNK-001', 'nama' => 'Chitato', 'harga_beli' => 5000, 'harga_jual' => 7000, 'stok' => 25, 'satuan' => 'pcs'],
            ['kategori_id' => 3, 'kode_barang' => 'SNK-002', 'nama' => 'Taro', 'harga_beli' => 4500, 'harga_jual' => 6500, 'stok' => 20, 'satuan' => 'pcs'],
            ['kategori_id' => 3, 'kode_barang' => 'SNK-003', 'nama' => 'Oreo', 'harga_beli' => 6000, 'harga_jual' => 8000, 'stok' => 15, 'satuan' => 'pcs'],
        ];

        foreach ($produks as $produk) {
            Produk::create($produk);
        }

        // Tambahkan 10 produk random untuk variasi
        Produk::factory(10)->create();
    }
}