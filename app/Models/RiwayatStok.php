<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    use HasFactory;

    protected $table = 'riwayat_stok';
    
    protected $fillable = [
        'produk_id',
        'jenis',
        'jumlah',
        'stok_sekarang',
        'keterangan',
        'referensi_id',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'stok_sekarang' => 'integer',
    ];

    // Relationship dengan produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // Scope untuk stok masuk
    public function scopeMasuk($query)
    {
        return $query->where('jenis', 'masuk');
    }

    // Scope untuk stok keluar
    public function scopeKeluar($query)
    {
        return $query->where('jenis', 'keluar');
    }
}