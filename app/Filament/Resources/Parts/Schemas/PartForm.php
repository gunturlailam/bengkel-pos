<?php

namespace App\Filament\Resources\Parts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PartForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Kategori Sparepart')
                    ->relationship('category', 'name') // Otomatis ambil nama kategori
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('sku')
                    ->label('Kode Barang (SKU)')
                    ->required()
                    ->maxLength(255),


                TextInput::make('name')
                    ->label('Nama Sparepart')
                    ->required()
                    ->maxLength(255),

                TextInput::make('buy_price')
                    ->label('Harga Beli')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp'), // GANTI JADI RUPIAH

                TextInput::make('sell_price')
                    ->label('Harga Jual')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp'), // GANTI JADI RUPIAH

                TextInput::make('stock')
                    ->label('Stok Saat Ini')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('min_stock')
                    ->label('Batas Stok Minimum')
                    ->required()
                    ->numeric()
                    ->default(5)
                    ->helperText('Sistem akan memberi peringatan jika stok di bawah angka ini.'),

                Toggle::make('is_active')
                    ->label('Status Aktif (Dijual?)')
                    ->default(true)
                    ->required(),
            ]);
    }
}
