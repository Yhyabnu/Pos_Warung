<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    
    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'nama_pelanggan',
        'subtotal',
        'pajak',
        'diskon',
        'total',
        'uang_dibayar',
        'kembalian',
        'metode_pembayaran',
        'status',
        'catatan',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'pajak' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total' => 'decimal:2',
        'uang_dibayar' => 'decimal:2',
        'kembalian' => 'decimal:2',
    ];

    // Relationship dengan user (kasir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship dengan detail transaksi
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    // Alias untuk kompatibilitas dengan kode yang sudah ada
    public function details()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    // Event untuk generate kode transaksi otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            if (empty($transaksi->kode_transaksi)) {
                $date = now()->format('Ymd');
                $lastTransaction = self::whereDate('created_at', today())->latest()->first();
                $sequence = $lastTransaction ? (int) substr($lastTransaction->kode_transaksi, -3) + 1 : 1;
                $transaksi->kode_transaksi = 'INV-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    // Scope untuk transaksi hari ini
    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Scope untuk transaksi bulan ini
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }
}