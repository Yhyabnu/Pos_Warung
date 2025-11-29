@php
    $quickProducts = \App\Models\Produk::where('aktif', true)
        ->orderBy('nama')
        ->limit(6)
        ->get();
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"> {{-- DARK MODE TEXT --}}
        Produk Cepat (Klik untuk tambah)
    </label>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
        @foreach($quickProducts as $produk)
            <button
                type="button"
                wire:click="tambahKeKeranjang({{ $produk->id }})"
                class="p-3 text-left border border-gray-200 dark:border-gray-600 rounded-lg hover:border-amber-300 dark:hover:border-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500 bg-white dark:bg-gray-800" {{-- DARK MODE BUTTON --}}
                wire:key="quick-{{ $produk->id }}"
            >
                <div class="font-medium text-sm text-gray-900 dark:text-white line-clamp-1">{{ $produk->nama }}</div> {{-- DARK MODE TEXT --}}
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $produk->kode_barang }}</div> {{-- DARK MODE TEXT --}}
                <div class="text-sm font-semibold text-amber-600 dark:text-amber-400 mt-1"> {{-- DARK MODE TEXT --}}
                    Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                </div>
            </button>
        @endforeach
    </div>
</div>