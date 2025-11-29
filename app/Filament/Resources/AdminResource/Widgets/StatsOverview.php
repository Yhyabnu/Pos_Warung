<?php

namespace App\Filament\Widgets;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        // Total penjualan hari ini
        $penjualanHariIni = Transaksi::whereDate('created_at', today())
            ->where('status', 'selesai')
            ->sum('total');

        // Total transaksi hari ini
        $transaksiHariIni = Transaksi::whereDate('created_at', today())
            ->where('status', 'selesai')
            ->count();

        // Produk dengan stok menipis
        $stokMenipis = Produk::where('stok', '<=', DB::raw('stok_minimum'))
            ->where('aktif', true)
            ->count();

        // Total produk aktif
        $totalProduk = Produk::where('aktif', true)->count();

        return [
            Stat::make('Penjualan Hari Ini', 'Rp ' . number_format($penjualanHariIni, 0, ',', '.'))
                ->description('Total pendapatan hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($this->getChartData()),

            Stat::make('Transaksi Hari Ini', $transaksiHariIni)
                ->description('Jumlah transaksi sukses')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),

            Stat::make('Stok Menipis', $stokMenipis)
                ->description('Produk perlu restock')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($stokMenipis > 0 ? 'danger' : 'success'),

            Stat::make('Total Produk', $totalProduk)
                ->description('Produk aktif di sistem')
                ->descriptionIcon('heroicon-m-cube')
                ->color('gray'),
        ];
    }

    private function getChartData(): array
    {
        // Data penjualan 7 hari terakhir untuk chart
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $total = Transaksi::whereDate('created_at', $date)
                ->where('status', 'selesai')
                ->sum('total');
            $data[] = $total / 1000; // Convert to thousands for better chart scale
        }
        return $data;
    }
}