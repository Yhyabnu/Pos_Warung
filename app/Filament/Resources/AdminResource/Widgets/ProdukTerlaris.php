<?php

namespace App\Filament\Widgets;

use App\Models\Produk;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProdukTerlaris extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Produk::withCount(['detailTransaksis as total_terjual' => function (Builder $query) {
                    $query->select(DB::raw('COALESCE(SUM(jumlah), 0)'));
                }])
                ->where('aktif', true)
                ->orderBy('total_terjual', 'desc')
                ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->circular(),

                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Produk'),

                Tables\Columns\TextColumn::make('kode_barang')
                    ->searchable()
                    ->sortable()
                    ->label('Kode'),

                Tables\Columns\TextColumn::make('total_terjual')
                    ->numeric()
                    ->sortable()
                    ->label('Terjual')
                    ->formatStateUsing(fn ($state) => $state . ' pcs'),

                Tables\Columns\TextColumn::make('stok')
                    ->numeric()
                    ->sortable()
                    ->label('Stok Tersedia')
                    ->color(fn ($record) => $record->stok <= $record->stok_minimum ? 'danger' : 'gray'),

                Tables\Columns\TextColumn::make('harga_jual')
                    ->money('IDR')
                    ->sortable()
                    ->label('Harga Jual'),
            ])
            ->heading('5 Produk Terlaris')
            ->description('Produk dengan penjualan tertinggi');
    }
}