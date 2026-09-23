<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label('Pilih Pemilik Kendaraan')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('plate_number')
                    ->label('Nomor Polisi (Plat Nomor)')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true), // Biar bisa di-update tanpa error duplikat

                Select::make('type')
                    ->label('Jenis Kendaraan')
                    ->options([
                        'Motor' => 'Motor',
                        'Mobil' => 'Mobil',
                    ])
                    ->required(),

                TextInput::make('brand')
                    ->label('Merek (Misal: Honda, Yamaha)')
                    ->required()
                    ->maxLength(255),

                TextInput::make('model')
                    ->label('Model / Tipe (Misal: Vario, Brio)')
                    ->maxLength(255),

                TextInput::make('year')
                    ->label('Tahun Pembuatan')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(date('Y') + 1),

                TextInput::make('color')
                    ->label('Warna Kendaraan')
                    ->maxLength(50),
            ]);
    }
}
