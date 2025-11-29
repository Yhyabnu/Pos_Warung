<div class="space-y-3">
    <!-- Main Process Button -->
    <button
        type="button"
        wire:click="prosesTransaksi"
        style="
            width: 100%;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            cursor: {{ $keranjang->isEmpty() || $uangDibayar < $total ? 'not-allowed' : 'pointer' }};
            background-color: {{ $keranjang->isEmpty() ? '#9CA3AF' : ($uangDibayar < $total ? '#F59E0B' : '#16A34A') }};
            color: {{ $keranjang->isEmpty() ? '#374151' : 'white' }};
        "
        class="dark:opacity-90 dark:hover:opacity-100 transition-opacity" {{-- TAMBAH DARK MODE --}}
        {{ $keranjang->isEmpty() || $uangDibayar < $total ? 'disabled' : '' }}
    >
        @if($keranjang->isEmpty())
            ⚠️ KERANJANG KOSONG
        @elseif($uangDibayar < $total)
            💰 BAYAR KURANG: Rp {{ number_format($total - $uangDibayar, 0, ',', '.') }}
        @else
            ✅ PROSES TRANSAKSI
        @endif
    </button>

    <button
        type="button"
        wire:click="resetKeranjang"
        style="
            width: 100%;
            padding: 8px 16px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            background: white;
            color: #374151;
            font-size: 14px;
            font-weight: 500;
        "
        class="dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 transition-colors" {{-- TAMBAH DARK MODE --}}
        {{ $keranjang->isEmpty() ? 'disabled' : '' }}
    >
        🔄 RESET KERANJANG
    </button>

    <!-- Quick Actions -->
    @if(!$keranjang->isEmpty())
    <div class="pt-3 border-t border-gray-200 dark:border-gray-700"> {{-- DARK MODE BORDER --}}
        <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Quick Actions:</div> {{-- DARK MODE TEXT --}}
        <div class="grid grid-cols-2 gap-2">
            <button
                type="button"
                wire:click="$set('uangDibayar', {{ $total }})"
                class="px-3 py-2 text-xs bg-blue-500 hover:bg-blue-600 text-white rounded transition-colors duration-200 dark:bg-blue-600 dark:hover:bg-blue-700" {{-- DARK MODE --}}
            >
                Exact Pay
            </button>
            <button
                type="button"
                wire:click="$set('uangDibayar', {{ ceil($total / 1000) * 1000 }})"
                class="px-3 py-2 text-xs bg-green-500 hover:bg-green-600 text-white rounded transition-colors duration-200 dark:bg-green-600 dark:hover:bg-green-700" {{-- DARK MODE --}}
            >
                Round Up
            </button>
        </div>
    </div>
    @endif
</div>