<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Filament\Resources\ProdukResource\RelationManagers;
use App\Models\Produk;
use App\Models\Kategori;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $modelLabel = 'Produk';

    protected static ?string $pluralModelLabel = 'Produk';

    protected static ?string $navigationGroup = 'Manajemen Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kategori_id')
                    ->relationship('kategori', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Kategori'),
                
                Forms\Components\TextInput::make('kode_barang')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->placeholder('Kode barang unik'),
                
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Nama produk'),
                
                Forms\Components\Textarea::make('deskripsi')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->placeholder('Deskripsi produk'),
                
                Forms\Components\TextInput::make('harga_beli')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->placeholder('Harga beli'),
                
                Forms\Components\TextInput::make('harga_jual')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->placeholder('Harga jual'),
                
                Forms\Components\TextInput::make('stok')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->placeholder('Stok saat ini'),
                
                Forms\Components\TextInput::make('stok_minimum')
                    ->required()
                    ->numeric()
                    ->default(5)
                    ->placeholder('Stok minimum peringatan'),
                
                Forms\Components\TextInput::make('satuan')
                    ->required()
                    ->maxLength(255)
                    ->default('pcs')
                    ->placeholder('Satuan (pcs, kg, etc)'),
                
                Forms\Components\FileUpload::make('gambar')
                    ->image()
                    ->directory('produk')
                    ->columnSpanFull()
                    ->label('Gambar Produk'),
                
                Forms\Components\Toggle::make('aktif')
                    ->required()
                    ->default(true)
                    ->label('Status Aktif'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->circular(),
                
                Tables\Columns\TextColumn::make('kode_barang')
                    ->searchable()
                    ->sortable()
                    ->label('Kode'),
                
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('kategori.nama')
                    ->searchable()
                    ->sortable()
                    ->label('Kategori'),
                
                Tables\Columns\TextColumn::make('harga_beli')
                    ->money('IDR')
                    ->sortable()
                    ->label('Harga Beli'),
                
                Tables\Columns\TextColumn::make('harga_jual')
                    ->money('IDR')
                    ->sortable()
                    ->label('Harga Jual'),
                
                Tables\Columns\TextColumn::make('stok')
                    ->numeric()
                    ->sortable()
                    ->color(fn (Produk $record) => $record->isStokMenipis() ? 'danger' : 'success'),
                
                Tables\Columns\TextColumn::make('satuan')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('aktif')
                    ->boolean()
                    ->label('Aktif'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->relationship('kategori', 'nama')
                    ->searchable()
                    ->preload()
                    ->label('Filter by Kategori'),
                
                Tables\Filters\TernaryFilter::make('aktif')
                    ->label('Status Aktif'),
                
                Tables\Filters\Filter::make('stok_menipis')
                    ->label('Stok Menipis')
                    ->query(fn (Builder $query) => $query->whereColumn('stok', '<=', 'stok_minimum')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\RiwayatStoksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}