<div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"> {{-- DARK MODE BACKGROUND & BORDER --}}
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Input Produk</h2> {{-- DARK MODE TEXT --}}
    
    <div class="space-y-4">
        <!-- Search Input -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"> {{-- DARK MODE TEXT --}}
                Scan/Ketik Kode Barang atau Nama Produk
            </label>
            <div class="relative">
                <input
                    type="text"
                    wire:model="kodeBarangInput"
                    wire:keydown.enter.prevent="cariProduk($event.target.value)"
                    placeholder="Ketik kode barang atau nama produk lalu tekan Enter"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" {{-- DARK MODE INPUT --}}
                >
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"> {{-- DARK MODE ICON --}}
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Quick Products Component -->
        @include('filament.pages.kasir.quick-products')
    </div>
</div>