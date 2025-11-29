<x-filament-panels::page>
    <div class="dark:bg-gray-900 dark:text-gray-100 min-h-screen py-6"> {{-- WRAPPER DARK MODE --}}
        <x-filament-panels::form wire:submit="prosesTransaksi">
            {{ $this->form }}

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" wire:loading.class="opacity-50" wire:target="prosesTransaksi,prosesBulkInput">
                <!-- Kolom Kiri: Input Produk & Keranjang -->
                <div class="lg:col-span-2 space-y-6">
                    @include('filament.pages.kasir.product-search')
                    @include('filament.pages.kasir.cart-items')
                </div>

                <!-- Kolom Kanan: Ringkasan & Pembayaran -->
                <div class="space-y-6">
                    @include('filament.pages.kasir.transaction-summary')
                    @include('filament.pages.kasir.payment-section')
                    @include('filament.pages.kasir.action-buttons')
                </div>
            </div>
        </x-filament-panels::form>

        @include('filament.pages.kasir.scripts')
    </div> {{-- END WRAPPER --}}
</x-filament-panels::page>