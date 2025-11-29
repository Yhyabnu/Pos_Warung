<div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"> {{-- DARK MODE BACKGROUND & BORDER --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Keranjang Belanja</h2> {{-- DARK MODE TEXT --}}
        <div class="flex items-center space-x-3">
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $keranjang->count() }} item</span> {{-- DARK MODE TEXT --}}
            @if(!$keranjang->isEmpty())
                <button
                    type="button"
                    wire:click="resetKeranjang"
                    wire:loading.attr="disabled"
                    class="px-3 py-1.5 text-xs bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-600 dark:hover:bg-red-700" {{-- DARK MODE --}}
                >
                    <span wire:loading.remove>Kosongkan</span>
                    <span wire:loading>Loading...</span>
                </button>
            @endif
        </div>
    </div>

    @if($keranjang->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"> {{-- DARK MODE ICON --}}
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Keranjang kosong</h3> {{-- DARK MODE TEXT --}}
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tambahkan produk untuk memulai transaksi</p> {{-- DARK MODE TEXT --}}
        </div>
    @else
        <div class="space-y-3 max-h-96 overflow-y-auto pr-2" wire:sortable="updateCartOrder">
            @foreach($keranjang as $index => $item)
                <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:border-amber-300 dark:hover:border-amber-500 transition-colors duration-200" {{-- DARK MODE BORDER --}}
                     wire:key="cart-item-{{ $item['id'] }}-{{ $item['jumlah'] }}">
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-gray-900 dark:text-white truncate">{{ $item['nama'] }}</div> {{-- DARK MODE TEXT --}}
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item['kode_barang'] }}</div> {{-- DARK MODE TEXT --}}
                        <div class="text-sm font-semibold text-amber-600 dark:text-amber-400 mt-1 flex items-center flex-wrap gap-2"> {{-- DARK MODE TEXT --}}
                            <span>Rp {{ number_format($item['harga_jual'], 0, ',', '.') }} ×</span>
                            <div class="flex items-center space-x-1">
                                <button
                                    type="button"
                                    wire:click="updateJumlah({{ $item['id'] }}, {{ $item['jumlah'] - 1 }})"
                                    class="w-6 h-6 flex items-center justify-center border border-gray-300 dark:border-gray-500 rounded hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" {{-- DARK MODE --}}
                                    {{ $item['jumlah'] <= 1 ? 'disabled' : '' }}
                                >
                                    <svg class="w-3 h-3 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"> {{-- DARK MODE ICON --}}
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                
                                <input 
                                    type="number" 
                                    wire:change="updateJumlah({{ $item['id'] }}, $event.target.value)"
                                    value="{{ $item['jumlah'] }}"
                                    min="1"
                                    max="{{ $item['stok'] }}"
                                    class="w-12 text-center border border-gray-300 dark:border-gray-500 rounded py-0.5 text-sm focus:ring-1 focus:ring-amber-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{-- DARK MODE --}}
                                />
                                
                                <button
                                    type="button"
                                    wire:click="updateJumlah({{ $item['id'] }}, {{ $item['jumlah'] + 1 }})"
                                    class="w-6 h-6 flex items-center justify-center border border-gray-300 dark:border-gray-500 rounded hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" {{-- DARK MODE --}}
                                    {{ $item['jumlah'] >= $item['stok'] ? 'disabled' : '' }}
                                >
                                    <svg class="w-3 h-3 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"> {{-- DARK MODE ICON --}}
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                            <span>= Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="hapusDariKeranjang({{ $item['id'] }})"
                        class="ml-3 p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500" {{-- DARK MODE --}}
                        title="Hapus dari keranjang"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>
        
        <!-- Cart Summary -->
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700"> {{-- DARK MODE BORDER --}}
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 dark:text-gray-400">Total Items:</span> {{-- DARK MODE TEXT --}}
                <span class="font-semibold dark:text-white">{{ $keranjang->sum('jumlah') }} pcs</span> {{-- DARK MODE TEXT --}}
            </div>
        </div>
    @endif
</div>