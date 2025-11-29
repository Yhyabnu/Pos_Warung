<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    
    protected $fillable = [
        'kategori_id',
        'kode_barang',
        'nama',
        'deskripsi',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimum',
        'satuan',
        'gambar',
        'aktif',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'stok' => 'integer',
        'stok_minimum' => 'integer',
        'aktif' => 'boolean',
    ];

    // Relationship dengan kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Relationship dengan detail transaksi
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    // Relationship dengan riwayat stok
    public function riwayatStoks()
    {
        return $this->hasMany(RiwayatStok::class);
    }

    // Scope untuk produk aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Scope untuk stok menipis
    public function scopeStokMenipis($query)
    {
        return $query->where('stok', '<=', \DB::raw('stok_minimum'));
    }

    // Helper method untuk cek stok menipis
    public function isStokMenipis()
    {
        return $this->stok <= $this->stok_minimum;
    }
}