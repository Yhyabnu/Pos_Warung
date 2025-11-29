<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;

class KasirSimpleController extends Controller
{
    public function index(Request $request)
    {
        // Handle POST requests (form submissions)
        if ($request->isMethod('post')) {
            return $this->handlePostRequest($request);
        }
        
        // Handle GET requests (display page)
        return $this->handleGetRequest($request);
    }
    
    public function cetakStruk($id)
    {
        $transaksi = Transaksi::with(['details', 'user'])
            ->where('id', $id)
            ->firstOrFail();
            
        return view('struk-transaksi', compact('transaksi'));
    }
    
    private function handleGetRequest(Request $request)
    {
        $searchQuery = $request->get('search', '');
        $products = $this->getProducts($searchQuery);
        $keranjang = session('keranjang', []);
        $totals = $this->hitungTotal($keranjang);
        
        // Ambil data kategori dari database
        $categories = Kategori::aktif()->orderBy('nama')->get();
        
        // Get last transaction for print modal
        $lastTransactionId = session('last_transaction_id');
        $lastTransactionCode = session('last_transaction_code');
        $showPrintModal = session('show_print_modal', false);
        
        return view('kasir-simple', compact(
            'products', 
            'keranjang', 
            'totals', 
            'categories',
            'searchQuery', 
            'lastTransactionId',
            'lastTransactionCode',
            'showPrintModal'
        ));
    }
    
    private function handlePostRequest(Request $request)
    {
        $action = $request->input('action');
        
        switch ($action) {
            case 'tambah_produk':
                return $this->tambahProduk($request);
                
            case 'update_jumlah':
                return $this->updateJumlah($request);
                
            case 'hapus_produk':
                return $this->hapusProduk($request);
                
            case 'proses_transaksi':
                return $this->prosesTransaksi($request);
                
            case 'reset_keranjang':
                return $this->resetKeranjang();
                
            case 'set_exact_payment':
                return $this->setExactPayment($request);
                
            case 'set_round_payment':
                return $this->setRoundPayment($request);
                
            default:
                return redirect()->back()->with('error', 'Aksi tidak dikenali');
        }
    }
    
    private function getProducts($searchQuery = '')
    {
        $query = Produk::with('kategori')
            ->where('aktif', true);
        
        if (!empty($searchQuery)) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('nama', 'like', '%' . $searchQuery . '%')
                  ->orWhere('kode_barang', 'like', '%' . $searchQuery . '%');
            });
        }
        
        return $query->orderBy('nama')->limit(24)->get();
    }
    
    private function hitungTotal($keranjang)
    {
        $subtotal = 0;
        foreach ($keranjang as $item) {
            $subtotal += $item['subtotal'];
        }
        
        $diskon = 0;
        $pajak = 0;
        $total = $subtotal - $diskon + $pajak;
        
        return [
            'subtotal' => $subtotal,
            'diskon' => $diskon,
            'pajak' => $pajak,
            'total' => $total
        ];
    }
    
    private function tambahProduk(Request $request)
    {
        $produkId = $request->input('produk_id');
        $jumlah = $request->input('jumlah', 1);
        
        $produk = Produk::find($produkId);
        
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }
        
        // Cek stok
        if ($produk->stok < $jumlah) {
            return redirect()->back()->with('error', "Stok {$produk->nama} tidak cukup. Stok tersedia: {$produk->stok}");
        }
        
        $keranjang = session('keranjang', []);
        
        // Cek jika produk sudah ada di keranjang
        $existingKey = $this->findProductInCart($keranjang, $produkId);
        
        if ($existingKey !== false) {
            // Update existing item
            $newJumlah = $keranjang[$existingKey]['jumlah'] + $jumlah;
            
            if ($produk->stok < $newJumlah) {
                return redirect()->back()->with('error', "Stok tidak cukup untuk penambahan. Butuh: {$newJumlah}, Stok: {$produk->stok}");
            }
            
            $keranjang[$existingKey]['jumlah'] = $newJumlah;
            $keranjang[$existingKey]['subtotal'] = $keranjang[$existingKey]['harga_jual'] * $newJumlah;
        } else {
            // Add new item
            $keranjang[] = [
                'id' => $produk->id,
                'kode_barang' => $produk->kode_barang,
                'nama' => $produk->nama,
                'harga_jual' => $produk->harga_jual,
                'jumlah' => $jumlah,
                'subtotal' => $produk->harga_jual * $jumlah,
                'stok' => $produk->stok,
                'kategori_id' => $produk->kategori_id,
            ];
        }
        
        session(['keranjang' => $keranjang]);
        
        return redirect()->back()->with('success', "{$produk->nama} ditambahkan");
    }
    
    private function updateJumlah(Request $request)
    {
        $produkId = $request->input('produk_id');
        $jumlah = $request->input('jumlah');
        
        if ($jumlah < 1) {
            return $this->hapusProduk($request);
        }
        
        $keranjang = session('keranjang', []);
        $key = $this->findProductInCart($keranjang, $produkId);
        
        if ($key !== false) {
            $produk = Produk::find($produkId);
            
            if ($produk && $produk->stok < $jumlah) {
                return redirect()->back()->with('error', "Stok tidak cukup. Stok tersedia: {$produk->stok}");
            }
            
            $keranjang[$key]['jumlah'] = $jumlah;
            $keranjang[$key]['subtotal'] = $keranjang[$key]['harga_jual'] * $jumlah;
            
            session(['keranjang' => $keranjang]);
        }
        
        return redirect()->back();
    }
    
    private function hapusProduk(Request $request)
    {
        $produkId = $request->input('produk_id');
        $keranjang = session('keranjang', []);
        
        $key = $this->findProductInCart($keranjang, $produkId);
        
        if ($key !== false) {
            $produkNama = $keranjang[$key]['nama'];
            unset($keranjang[$key]);
            session(['keranjang' => array_values($keranjang)]); // Reindex array
            
            return redirect()->back()->with('warning', "{$produkNama} dihapus");
        }
        
        return redirect()->back();
    }
    
    private function prosesTransaksi(Request $request)
    {
        $keranjang = session('keranjang', []);
        $uangDibayar = $request->input('uang_dibayar', 0);
        $namaPelanggan = $request->input('nama_pelanggan', '');
        $metodePembayaran = $request->input('metode_pembayaran', 'tunai');
        
        $totals = $this->hitungTotal($keranjang);
        
        // Validasi
        if (empty($keranjang)) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }
        
        if ($uangDibayar < $totals['total']) {
            return redirect()->back()->with('error', 'Uang dibayar kurang');
        }
        
        // Validasi stok
        $stokErrors = [];
        foreach ($keranjang as $item) {
            $produk = Produk::find($item['id']);
            $stokTersedia = $produk ? $produk->stok : 0;
            if (!$produk || $stokTersedia < $item['jumlah']) {
                $stokErrors[] = "{$item['nama']} (butuh: {$item['jumlah']}, stok: {$stokTersedia})";
            }
        }
        
        if (!empty($stokErrors)) {
            return redirect()->back()->with('error', 'Stok tidak cukup: ' . implode(', ', $stokErrors));
        }
        
        try {
            $lastTransaction = null;
            
            DB::transaction(function () use ($keranjang, $totals, $uangDibayar, $namaPelanggan, $metodePembayaran, &$lastTransaction) {
                $kembalian = max(0, $uangDibayar - $totals['total']);
                
                // Generate kode transaksi
                $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . str_pad(Transaksi::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
                
                // Buat transaksi
                $transaksi = Transaksi::create([
                    'kode_transaksi' => $kodeTransaksi,
                    'user_id' => auth()->id(),
                    'subtotal' => $totals['subtotal'],
                    'diskon' => $totals['diskon'],
                    'pajak' => $totals['pajak'],
                    'total' => $totals['total'],
                    'uang_dibayar' => $uangDibayar,
                    'kembalian' => $kembalian,
                    'metode_pembayaran' => $metodePembayaran,
                    'status' => 'selesai',
                    'catatan' => null,
                ]);
                
                $lastTransaction = $transaksi;
                
                // Buat detail transaksi
                $detailData = [];
                $stokUpdates = [];
                
                foreach ($keranjang as $item) {
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
                
                DetailTransaksi::insert($detailData);
                
                // Update stok
                foreach ($stokUpdates as $produkId => $jumlah) {
                    Produk::where('id', $produkId)->decrement('stok', $jumlah);
                }
                
                // Clear keranjang
                session()->forget('keranjang');
                
                // Simpan data transaksi untuk cetak struk
                session([
                    'last_transaction_id' => $transaksi->id,
                    'last_transaction_code' => $transaksi->kode_transaksi,
                    'show_print_modal' => true
                ]);
            });
            
            return redirect()->route('kasir.simple')->with([
                'success' => "Transaksi {$lastTransaction->kode_transaksi} berhasil!",
                'show_print_modal' => true
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi error: ' . $e->getMessage());
        }
    }
    
    private function resetKeranjang()
    {
        session()->forget('keranjang');
        return redirect()->back()->with('info', 'Keranjang telah direset');
    }
    
    private function setExactPayment(Request $request)
    {
        $keranjang = session('keranjang', []);
        $totals = $this->hitungTotal($keranjang);
        
        return redirect()->back()->withInput(['uang_dibayar' => $totals['total']]);
    }
    
    private function setRoundPayment(Request $request)
    {
        $keranjang = session('keranjang', []);
        $totals = $this->hitungTotal($keranjang);
        $rounded = ceil($totals['total'] / 1000) * 1000;
        
        return redirect()->back()->withInput(['uang_dibayar' => $rounded]);
    }
    
    private function findProductInCart($keranjang, $produkId)
    {
        foreach ($keranjang as $key => $item) {
            if ($item['id'] == $produkId) {
                return $key;
            }
        }
        return false;
    }
}