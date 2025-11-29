<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi';
    
    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'kode_barang',
        'nama_produk',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationship dengan transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // Relationship dengan produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // Event untuk update stok ketika detail transaksi dibuat
    protected static function boot()
    {
        parent::boot();

        static::created(function ($detailTransaksi) {
            // Update stok produk
            $produk = $detailTransaksi->produk;
            if ($produk) {
                $produk->stok -= $detailTransaksi->jumlah;
                $produk->save();

                // Buat riwayat stok jika model RiwayatStok ada
                if (class_exists('App\Models\RiwayatStok')) {
                    \App\Models\RiwayatStok::create([
                        'produk_id' => $produk->id,
                        'jenis' => 'keluar',
                        'jumlah' => $detailTransaksi->jumlah,
                        'stok_sekarang' => $produk->stok,
                        'keterangan' => 'Penjualan - ' . $detailTransaksi->transaksi->kode_transaksi,
                        'referensi_id' => $detailTransaksi->transaksi_id,
                    ]);
                }
            }
        });
    }
}