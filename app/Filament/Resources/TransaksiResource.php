<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use App\Models\Transaksi;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $modelLabel = 'Transaksi';

    protected static ?string $pluralModelLabel = 'Transaksi';

    protected static ?string $navigationGroup = 'Manajemen Penjualan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_transaksi')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->placeholder('Kode transaksi otomatis')
                    ->disabled()
                    ->dehydrated(),
                
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Kasir'),
                
                Forms\Components\TextInput::make('nama_pelanggan')
                    ->maxLength(255)
                    ->placeholder('Nama pelanggan (opsional)'),
                
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->placeholder('Subtotal'),
                
                Forms\Components\TextInput::make('pajak')
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp')
                    ->placeholder('Pajak'),
                
                Forms\Components\TextInput::make('diskon')
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp')
                    ->placeholder('Diskon'),
                
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->placeholder('Total'),
                
                Forms\Components\TextInput::make('uang_dibayar')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->placeholder('Uang dibayar'),
                
                Forms\Components\TextInput::make('kembalian')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->placeholder('Kembalian'),
                
                Forms\Components\Select::make('metode_pembayaran')
                    ->options([
                        'tunai' => 'Tunai',
                        'qris' => 'QRIS',
                        'transfer' => 'Transfer',
                    ])
                    ->required()
                    ->default('tunai')
                    ->label('Metode Pembayaran'),
                
                Forms\Components\Select::make('status')
                    ->options([
                        'selesai' => 'Selesai',
                        'pending' => 'Pending',
                        'dibatalkan' => 'Dibatalkan',
                    ])
                    ->required()
                    ->default('selesai')
                    ->label('Status Transaksi'),
                
                Forms\Components\Textarea::make('catatan')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->placeholder('Catatan transaksi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_transaksi')
                    ->searchable()
                    ->sortable()
                    ->label('Kode Transaksi')
                    ->copyable()
                    ->copyMessage('Kode transaksi disalin!'),
                
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
                
                Tables\Columns\TextColumn::make('uang_dibayar')
                    ->money('IDR')
                    ->sortable()
                    ->label('Dibayar'),
                
                Tables\Columns\TextColumn::make('kembalian')
                    ->money('IDR')
                    ->sortable()
                    ->label('Kembalian'),
                
                Tables\Columns\BadgeColumn::make('metode_pembayaran')
                    ->colors([
                        'success' => 'tunai',
                        'warning' => 'qris', 
                        'info' => 'transfer',
                    ])
                    ->formatStateUsing(fn ($state) => strtoupper($state))
                    ->label('Pembayaran'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'selesai',
                        'warning' => 'pending',
                        'danger' => 'dibatalkan',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->label('Status'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal Transaksi'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Filter by Kasir'),
                
                Tables\Filters\SelectFilter::make('metode_pembayaran')
                    ->options([
                        'tunai' => 'Tunai',
                        'qris' => 'QRIS', 
                        'transfer' => 'Transfer',
                    ])
                    ->label('Filter by Pembayaran'),
                
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'selesai' => 'Selesai',
                        'pending' => 'Pending',
                        'dibatalkan' => 'Dibatalkan',
                    ])
                    ->label('Filter by Status'),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->label('Filter by Tanggal'),
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
            ])
            ->emptyStateActions([
                // Tidak ada create action karena transaksi harus dari kasir
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\DetailTransaksisRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            // 'view' => Pages\ViewTransaksi::route('/{record}'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}