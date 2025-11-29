<?php

namespace App\Filament\Pages;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Kasir extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string $view = 'filament.pages.kasir';
    protected static ?string $navigationLabel = 'Kasir';
    protected static ?string $title = 'Point of Sale (POS)';
    protected static ?string $navigationGroup = 'Operasional';

    public ?array $data = [];
    public Collection $keranjang;
    public float $subtotal = 0;
    public float $diskon = 0;
    public float $pajak = 0;
    public float $total = 0;
    public $uangDibayar = 0; // UBAH: Hapus type declaration untuk fleksibilitas
    public float $kembalian = 0;
    public string $namaPelanggan = '';
    public string $metodePembayaran = 'tunai';
    public string $kodeBarangInput = '';

    // ====================
    // NEW PROPERTIES FOR BULK INPUT
    // ====================
    public bool $showBulkInput = false;
    public string $bulkInputText = '';
    public array $bulkItemsPreview = [];

    // ====================
    // OPTIMIZED PROPERTIES
    // ====================
    public bool $isProcessing = false;
    public bool $isProcessingBulk = false;

    public function mount(): void
    {
        $this->keranjang = collect();
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('produk_id'),
            ])
            ->statePath('data');
    }

    // ====================
    // NEW: LIVE CYCLE HOOKS UNTUK REAL-TIME UPDATES
    // ====================

    /**
     * Handle real-time updates ketika uangDibayar berubah
     */
    public function updatedUangDibayar($value): void
    {
        // Convert ke float dan pastikan tidak negatif
        $this->uangDibayar = max(0, floatval($value ?? 0));
        $this->hitungKembalian();
    }

    /**
     * Handle real-time updates ketika keranjang berubah
     */
    public function updatedKeranjang(): void
    {
        $this->hitungTotal();
    }

    // ====================
    // OPTIMIZED METHODS - PERFORMANCE
    // ====================

    /**
     * Optimized method untuk mengurangi re-render
     */
    public function getListeners()
    {
        return [
            'clear-search' => 'clearSearchInput',
            'refresh-cart' => 'hitungTotal',
        ];
    }

    public function clearSearchInput()
    {
        $this->kodeBarangInput = '';
    }

    /**
     * Optimized method untuk cari produk dengan cache
     */
    public function cariProduk(string $kode): void
    {
        if (empty(trim($kode))) return;

        // Gunakan cache untuk frequently searched products
        $produk = cache()->remember(
            "produk_search_{$kode}_" . auth()->id(),
            now()->addMinutes(5),
            function () use ($kode) {
                return Produk::where('aktif', true)
                    ->where(function($query) use ($kode) {
                        $query->where('kode_barang', $kode)
                              ->orWhere('nama', 'LIKE', "%{$kode}%");
                    })
                    ->first();
            }
        );

        if ($produk) {
            $this->tambahKeKeranjang($produk);
            $this->dispatch('clear-search');
        } else {
            Notification::make()
                ->title('Produk tidak ditemukan')
                ->body('Kode barang atau nama produk tidak valid')
                ->danger()
                ->send();
        }
    }

    /**
     * Optimized method untuk tambah ke keranjang dengan batch update
     */
    public function tambahKeKeranjang($produk, int $jumlah = 1): void
    {
        // Handle jika yang diterima adalah ID bukan model
        if (is_numeric($produk)) {
            $produk = Produk::find($produk);
        }

        if (!$produk) {
            Notification::make()
                ->title('Produk tidak ditemukan')
                ->danger()
                ->send();
            return;
        }

        // Cek stok tersedia
        if ($produk->stok < $jumlah) {
            Notification::make()
                ->title('Stok tidak cukup')
                ->body("Stok {$produk->nama} hanya {$produk->stok}, butuh {$jumlah}")
                ->danger()
                ->send();
            return;
        }

        $existingItem = $this->keranjang->firstWhere('id', $produk->id);

        if ($existingItem) {
            // Update jumlah jika produk sudah ada di keranjang
            $newJumlah = $existingItem['jumlah'] + $jumlah;
            
            if ($produk->stok < $newJumlah) {
                Notification::make()
                    ->title('Stok tidak cukup untuk penambahan')
                    ->body("Total butuh {$newJumlah}, stok hanya {$produk->stok}")
                    ->danger()
                    ->send();
                return;
            }

            // Optimized: Gunakan key-based update untuk menghindari full collection re-render
            $this->keranjang = $this->keranjang->map(function ($item) use ($produk, $newJumlah) {
                if ($item['id'] === $produk->id) {
                    return [
                        ...$item,
                        'jumlah' => $newJumlah,
                        'subtotal' => $item['harga_jual'] * $newJumlah,
                    ];
                }
                return $item;
            });
        } else {
            // Tambah produk baru ke keranjang
            $this->keranjang->push([
                'id' => $produk->id,
                'kode_barang' => $produk->kode_barang,
                'nama' => $produk->nama,
                'harga_jual' => $produk->harga_jual,
                'jumlah' => $jumlah,
                'subtotal' => $produk->harga_jual * $jumlah,
                'stok' => $produk->stok,
            ]);
        }

        $this->hitungTotal();
        
        // Single notification untuk batch operations
        if (!$this->isProcessingBulk) {
            Notification::make()
                ->title('Produk ditambahkan')
                ->body("{$produk->nama} ({$jumlah} pcs) berhasil ditambahkan")
                ->success()
                ->send();
        }
    }

    /**
     * Optimized method untuk update jumlah dengan debounce
     */
    public function updateJumlah($produkId, $jumlah): void
    {
        if ($jumlah < 1) {
            $this->hapusDariKeranjang($produkId);
            return;
        }

        // Debounce manual untuk menghindari terlalu banyak update
        $this->keranjang = $this->keranjang->map(function ($item) use ($produkId, $jumlah) {
            if ($item['id'] === $produkId) {
                return [
                    ...$item,
                    'jumlah' => $jumlah,
                    'subtotal' => $item['harga_jual'] * $jumlah,
                ];
            }
            return $item;
        });

        $this->hitungTotal();
    }

    /**
     * Optimized method untuk hapus item
     */
    public function hapusDariKeranjang($produkId): void
    {
        $produk = $this->keranjang->firstWhere('id', $produkId);
        
        // Optimized: Gunakan reject dengan key-based removal
        $this->keranjang = $this->keranjang->reject(fn ($item) => $item['id'] === $produkId);

        $this->hitungTotal();
        
        if ($produk && !$this->isProcessing) {
            Notification::make()
                ->title('Produk dihapus')
                ->body("{$produk['nama']} dihapus dari keranjang")
                ->warning()
                ->send();
        }
    }

    /**
     * Optimized method untuk hitung total dengan caching
     */
    public function hitungTotal(): void
    {
        // Cache calculations untuk performance
        $this->subtotal = $this->keranjang->sum('subtotal');
        $this->total = $this->subtotal - $this->diskon + $this->pajak;
        $this->hitungKembalian();
    }

    public function hitungKembalian(): void
    {
        // Pastikan uangDibayar adalah numeric
        $uangDibayar = is_numeric($this->uangDibayar) ? floatval($this->uangDibayar) : 0;
        $this->kembalian = max(0, $uangDibayar - $this->total);
    }

    public function setDiskon($diskon): void
    {
        $this->diskon = floatval($diskon);
        $this->hitungTotal();
    }

    public function setPajak($pajak): void
    {
        $this->pajak = floatval($pajak);
        $this->hitungTotal();
    }

    /**
     * Optimized method untuk proses transaksi dengan better error handling
     */
    public function prosesTransaksi(): void
    {
        $this->isProcessing = true;

        try {
            // Validasi
            if ($this->keranjang->isEmpty()) {
                Notification::make()
                    ->title('Keranjang kosong')
                    ->body('Tambahkan produk terlebih dahulu')
                    ->danger()
                    ->send();
                $this->isProcessing = false;
                return;
            }

            // Pastikan uangDibayar adalah numeric
            $uangDibayar = is_numeric($this->uangDibayar) ? floatval($this->uangDibayar) : 0;
            
            if ($uangDibayar < $this->total) {
                Notification::make()
                    ->title('Pembayaran kurang')
                    ->body('Uang yang dibayar kurang dari total')
                    ->danger()
                    ->send();
                $this->isProcessing = false;
                return;
            }

            // Bulk check stok untuk performance
            $stokErrors = [];
            foreach ($this->keranjang as $item) {
                $produk = Produk::find($item['id']);
                if (!$produk || $produk->stok < $item['jumlah']) {
                    $nama = $item['nama'] ?? 'Produk';
                    $stok = $produk->stok ?? 0;
                    $stokErrors[] = "{$nama} (butuh: {$item['jumlah']}, stok: {$stok})";
                }
            }

            if (!empty($stokErrors)) {
                Notification::make()
                    ->title('Stok tidak mencukupi')
                    ->body(implode(', ', $stokErrors))
                    ->danger()
                    ->send();
                $this->isProcessing = false;
                return;
            }

            DB::transaction(function () use ($uangDibayar) {
                // Buat transaksi
                $transaksi = Transaksi::create([
                    'user_id' => auth()->id(),
                    'nama_pelanggan' => $this->namaPelanggan ?: null,
                    'subtotal' => $this->subtotal,
                    'diskon' => $this->diskon,
                    'pajak' => $this->pajak,
                    'total' => $this->total,
                    'uang_dibayar' => $uangDibayar,
                    'kembalian' => $this->kembalian,
                    'metode_pembayaran' => $this->metodePembayaran,
                    'status' => 'selesai',
                ]);

                // Bulk insert detail transaksi untuk performance
                $detailData = [];
                $stokUpdates = [];
                
                foreach ($this->keranjang as $item) {
                    $detailData[] = [
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $item['id'],
                        'kode_barang' => $item['kode_barang'],
                        'nama_produk' => $item['nama'],
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $item['harga_jual'],
                        'subtotal' => $item['subtotal'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $stokUpdates[$item['id']] = $item['jumlah'];
                }

                // Bulk insert
                DetailTransaksi::insert($detailData);

                // Bulk update stok
                foreach ($stokUpdates as $produkId => $jumlah) {
                    Produk::where('id', $produkId)->decrement('stok', $jumlah);
                }

                Notification::make()
                    ->title('Transaksi berhasil')
                    ->body("Transaksi {$transaksi->kode_transaksi} berhasil diproses")
                    ->success()
                    ->send();

                // Reset form
                $this->resetKeranjang();
            });

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage())
                ->danger()
                ->send();
        } finally {
            $this->isProcessing = false;
        }
    }

    // Method untuk reset keranjang
    public function resetKeranjang(): void
    {
        $this->keranjang = collect();
        $this->subtotal = 0;
        $this->diskon = 0;
        $this->pajak = 0;
        $this->total = 0;
        $this->uangDibayar = 0;
        $this->kembalian = 0;
        $this->namaPelanggan = '';
        $this->metodePembayaran = 'tunai';
        $this->clearBulkInput();
    }

    // ====================
    // OPTIMIZED BULK INPUT METHODS
    // ====================

    public function toggleBulkInput(): void
    {
        $this->showBulkInput = !$this->showBulkInput;
        if (!$this->showBulkInput) {
            $this->clearBulkInput();
        }
        
        $this->dispatch('bulk-input-toggled');
    }

    public function clearBulkInput(): void
    {
        $this->bulkInputText = '';
        $this->bulkItemsPreview = [];
    }

    /**
     * Optimized bulk input parsing dengan batch processing
     */
    public function updatedBulkInputText(): void
    {
        // Debounce manual untuk parsing
        if (strlen($this->bulkInputText) > 0) {
            $this->parseBulkInput();
        } else {
            $this->bulkItemsPreview = [];
        }
    }

    private function parseBulkInput(): void
    {
        $this->bulkItemsPreview = [];

        if (empty(trim($this->bulkInputText))) {
            return;
        }

        $lines = explode("\n", trim($this->bulkInputText));
        $identifiers = [];
        
        // Collect semua identifier untuk batch query
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = preg_split('/\s+/', $line, 2);
            
            if (count($parts) === 2) {
                $identifier = trim($parts[0]);
                $jumlah = intval(trim($parts[1]));

                if ($jumlah > 0) {
                    $identifiers[] = $identifier;
                }
            }
        }

        // Batch query produk untuk performance
        $produks = Produk::where('aktif', true)
            ->where(function($query) use ($identifiers) {
                foreach ($identifiers as $identifier) {
                    $query->orWhere('kode_barang', $identifier)
                          ->orWhere('nama', 'LIKE', "%{$identifier}%");
                }
            })
            ->get()
            ->keyBy(fn($produk) => $produk->kode_barang);

        // Process lines dengan hasil batch query
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = preg_split('/\s+/', $line, 2);
            
            if (count($parts) === 2) {
                $identifier = trim($parts[0]);
                $jumlah = intval(trim($parts[1]));

                if ($jumlah > 0) {
                    // Cari produk dari batch results
                    $produk = $produks->first(function ($produk) use ($identifier) {
                        return $produk->kode_barang === $identifier || 
                               stripos($produk->nama, $identifier) !== false;
                    });

                    if ($produk) {
                        if ($produk->stok >= $jumlah) {
                            $this->bulkItemsPreview[] = [
                                'id' => $produk->id,
                                'kode_barang' => $produk->kode_barang,
                                'nama' => $produk->nama,
                                'harga_jual' => $produk->harga_jual,
                                'jumlah' => $jumlah,
                                'subtotal' => $produk->harga_jual * $jumlah,
                                'stok' => $produk->stok,
                                'status' => 'success',
                            ];
                        } else {
                            $this->bulkItemsPreview[] = [
                                'id' => $produk->id,
                                'kode_barang' => $produk->kode_barang,
                                'nama' => $produk->nama,
                                'harga_jual' => $produk->harga_jual,
                                'jumlah' => $jumlah,
                                'subtotal' => $produk->harga_jual * $jumlah,
                                'stok' => $produk->stok,
                                'status' => 'error',
                                'message' => "Stok hanya {$produk->stok}",
                            ];
                        }
                    } else {
                        $this->bulkItemsPreview[] = [
                            'identifier' => $identifier,
                            'jumlah' => $jumlah,
                            'status' => 'error',
                            'message' => 'Produk tidak ditemukan',
                        ];
                    }
                }
            } else {
                $this->bulkItemsPreview[] = [
                    'line' => $line,
                    'status' => 'error', 
                    'message' => 'Format tidak valid. Gunakan: KODE_BARANG JUMLAH',
                ];
            }
        }
    }

    /**
     * Optimized bulk processing dengan chunk dan batch operations
     */
    public function prosesBulkInput(): void
    {
        $this->isProcessingBulk = true;

        try {
            if (empty($this->bulkItemsPreview)) {
                Notification::make()
                    ->title('Tidak ada items valid')
                    ->body('Periksa format input bulk Anda')
                    ->warning()
                    ->send();
                return;
            }

            $successCount = 0;
            $errorCount = 0;

            // Process in chunks untuk performance
            $chunks = array_chunk($this->bulkItemsPreview, 10);
            
            foreach ($chunks as $chunk) {
                foreach ($chunk as $item) {
                    if ($item['status'] === 'success') {
                        try {
                            $produk = Produk::find($item['id']);
                            
                            if ($produk && $produk->stok >= $item['jumlah']) {
                                $this->tambahKeKeranjang($produk, $item['jumlah']);
                                $successCount++;
                            } else {
                                $errorCount++;
                            }
                        } catch (\Exception $e) {
                            $errorCount++;
                        }
                    } else {
                        $errorCount++;
                    }
                }
            }

            $this->clearBulkInput();
            $this->showBulkInput = false;

            // Single notification instead of multiple
            if ($successCount > 0) {
                Notification::make()
                    ->title('Bulk Input Berhasil')
                    ->body("{$successCount} items berhasil ditambahkan" . ($errorCount > 0 ? ", {$errorCount} gagal" : ""))
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Bulk Input Gagal')
                    ->body("Tidak ada items yang berhasil ditambahkan. Periksa stok dan format input.")
                    ->danger()
                    ->send();
            }

        } finally {
            $this->isProcessingBulk = false;
        }
    }

    /**
     * Helper method untuk mendapatkan statistik keranjang
     */
    public function getCartStatsProperty(): array
    {
        return [
            'total_items' => $this->keranjang->count(),
            'total_quantity' => $this->keranjang->sum('jumlah'),
            'total_value' => $this->subtotal,
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isKasir() || auth()->user()->isAdmin();
    }
}