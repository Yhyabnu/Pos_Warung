<div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"> {{-- DARK MODE BACKGROUND & BORDER --}}
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ringkasan Transaksi</h2> {{-- DARK MODE TEXT --}}
    
    <div class="space-y-3">
        <div class="flex justify-between items-center py-2">
            <span class="text-gray-600 dark:text-gray-400">Subtotal:</span> {{-- DARK MODE TEXT --}}
            <span class="font-semibold dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span> {{-- DARK MODE TEXT --}}
        </div>
        
        <div class="flex justify-between items-center py-2">
            <span class="text-gray-600 dark:text-gray-400">Diskon:</span> {{-- DARK MODE TEXT --}}
            <div class="flex items-center space-x-2">
                <input 
                    type="number" 
                    wire:model.live="diskon"
                    wire:change="setDiskon($event.target.value)"
                    class="w-20 text-right border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition-colors bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{-- DARK MODE INPUT --}}
                    min="0"
                    placeholder="0"
                >
                <span class="text-sm text-gray-500 dark:text-gray-400">Rp</span> {{-- DARK MODE TEXT --}}
            </div>
        </div>
        
        <div class="flex justify-between items-center py-2">
            <span class="text-gray-600 dark:text-gray-400">Pajak:</span> {{-- DARK MODE TEXT --}}
            <div class="flex items-center space-x-2">
                <input 
                    type="number" 
                    wire:model.live="pajak"
                    wire:change="setPajak($event.target.value)"
                    class="w-20 text-right border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition-colors bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{-- DARK MODE INPUT --}}
                    min="0"
                    placeholder="0"
                >
                <span class="text-sm text-gray-500 dark:text-gray-400">Rp</span> {{-- DARK MODE TEXT --}}
            </div>
        </div>
        
        <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-2"> {{-- DARK MODE BORDER --}}
            <div class="flex justify-between items-center text-lg font-bold">
                <span class="text-gray-800 dark:text-white">TOTAL:</span> {{-- DARK MODE TEXT --}}
                <span class="text-amber-600 dark:text-amber-400">Rp {{ number_format($total, 0, ',', '.') }}</span> {{-- DARK MODE TEXT --}}
            </div>
        </div>

        <!-- Cart Stats -->
        @if(!$keranjang->isEmpty())
        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700"> {{-- DARK MODE BORDER --}}
            <div class="space-y-1 text-xs text-gray-500 dark:text-gray-400"> {{-- DARK MODE TEXT --}}
                <div class="flex justify-between">
                    <span>Items:</span>
                    <span class="font-medium dark:text-gray-300">{{ $keranjang->count() }}</span> {{-- DARK MODE TEXT --}}
                </div>
                <div class="flex justify-between">
                    <span>Total Qty:</span>
                    <span class="font-medium dark:text-gray-300">{{ $keranjang->sum('jumlah') }} pcs</span> {{-- DARK MODE TEXT --}}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>