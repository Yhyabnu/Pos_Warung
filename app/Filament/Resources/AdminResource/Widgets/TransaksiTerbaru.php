<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TransaksiTerbaru extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaksi::with('user')
                    ->where('status', 'selesai')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('kode_transaksi')
                    ->searchable()
                    ->sortable()
                    ->label('Kode Transaksi')
                    ->copyable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('Kasir'),

                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->searchable()
                    ->sortable()
                    ->label('Pelanggan')
                    ->placeholder('Tanpa nama'),

                Tables\Columns\TextColumn::make('total')
                    ->money('IDR')
                    ->sortable()
                    ->label('Total'),

                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->badge()
                    ->formatStateUsing(fn ($state) => strtoupper($state))
                    ->colors([
                        'success' => 'tunai',
                        'warning' => 'qris',
                        'info' => 'transfer',
                    ])
                    ->label('Pembayaran'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->label('Waktu'),
            ])
            ->heading('5 Transaksi Terbaru')
            ->description('Transaksi penjualan terkini');
    }
}