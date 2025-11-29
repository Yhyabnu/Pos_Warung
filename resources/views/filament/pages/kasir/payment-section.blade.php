<div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"> {{-- DARK MODE BACKGROUND & BORDER --}}
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pembayaran</h2> {{-- DARK MODE TEXT --}}
    
    <div class="space-y-4">
        <!-- Nama Pelanggan -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"> {{-- DARK MODE TEXT --}}
                Nama Pelanggan (Opsional)
            </label>
            <input
                type="text"
                wire:model="namaPelanggan"
                placeholder="Nama pelanggan"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" {{-- DARK MODE INPUT --}}
            >
        </div>

        <!-- Metode Pembayaran -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"> {{-- DARK MODE TEXT --}}
                Metode Pembayaran
            </label>
            <select
                wire:model="metodePembayaran"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{-- DARK MODE SELECT --}}
            >
                <option value="tunai">💰 Tunai</option>
                <option value="qris">📱 QRIS</option>
                <option value="transfer">🏦 Transfer</option>
            </select>
        </div>

        <!-- Uang Dibayar -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"> {{-- DARK MODE TEXT --}}
                Uang Dibayar
            </label>
            <input
                type="number"
                wire:model="uangDibayar"
                wire:blur="hitungKembalian"
                placeholder="0"
                min="0"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200 text-left font-semibold text-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" {{-- DARK MODE INPUT --}}
                x-data
                x-ref="paymentInput"
                @keydown.f2.prevent="$refs.paymentInput.focus(); $refs.paymentInput.select();"
            >
        </div>

        <!-- Kembalian -->
        @if($kembalian > 0)
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 animate-pulse"> {{-- DARK MODE ALERT --}}
                <div class="flex justify-between items-center">
                    <span class="text-green-800 dark:text-green-300 font-semibold">KEMBALIAN:</span> {{-- DARK MODE TEXT --}}
                    <span class="text-xl font-bold text-green-600 dark:text-green-400"> {{-- DARK MODE TEXT --}}
                        Rp {{ number_format($kembalian, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        @elseif($uangDibayar > 0 && $uangDibayar < $total)
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3"> {{-- DARK MODE ALERT --}}
                <div class="flex justify-between items-center text-red-800 dark:text-red-300"> {{-- DARK MODE TEXT --}}
                    <span class="font-semibold">KURANG:</span>
                    <span class="font-bold">
                        Rp {{ number_format($total - $uangDibayar, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>