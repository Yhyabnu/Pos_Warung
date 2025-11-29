<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    
    protected $fillable = [
        'nama',
        'deskripsi',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Relationship dengan produk
    public function produks()
    {
        return $this->hasMany(Produk::class);
    }

    // Scope untuk kategori aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}