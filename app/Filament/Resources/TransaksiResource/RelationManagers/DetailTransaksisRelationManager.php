<?php

namespace App\Filament\Resources\TransaksiResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailTransaksisRelationManager extends RelationManager
{
    protected static string $relationship = 'detailTransaksis';

    protected static ?string $title = 'Detail Items Transaksi';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_barang')
                    ->required()
                    ->maxLength(255)
                    ->label('Kode Barang'),
                
                Forms\Components\TextInput::make('nama_produk')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Produk'),
                
                Forms\Components\TextInput::make('jumlah')
                    ->required()
                    ->numeric()
                    ->label('Jumlah'),
                
                Forms\Components\TextInput::make('harga_satuan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Harga Satuan'),
                
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Subtotal'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_produk')
            ->columns([
                Tables\Columns\TextColumn::make('kode_barang')
                    ->label('Kode Barang'),
                
                Tables\Columns\TextColumn::make('nama_produk')
                    ->label('Nama Produk'),
                
                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah'),
                
                Tables\Columns\TextColumn::make('harga_satuan')
                    ->money('IDR')
                    ->label('Harga Satuan'),
                
                Tables\Columns\TextColumn::make('subtotal')
                    ->money('IDR')
                    ->label('Subtotal'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tidak ada create action karena detail harus dari transaksi
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}