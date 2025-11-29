<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PenjualanChart extends ChartWidget
{
    protected static ?string $heading = 'Trend Penjualan 7 Hari Terakhir';
    
    protected static ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        // Data penjualan 7 hari terakhir dengan query manual
        $data = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $formattedDate = $date->format('Y-m-d');
            
            $total = Transaksi::whereDate('created_at', $formattedDate)
                ->where('status', 'selesai')
                ->sum('total');
            
            $data[] = $total / 1000; // Convert to thousands for better scale
            $labels[] = $date->format('d M'); // Format: 11 Nov, 12 Nov, etc.
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan (Ribu Rupiah)',
                    'data' => $data,
                    'borderColor' => '#f59e0b', // Amber color
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    public function getDescription(): ?string
    {
        return 'Grafik perkembangan penjualan harian dalam ribuan rupiah';
    }
}