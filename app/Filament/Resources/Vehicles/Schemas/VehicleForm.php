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
                TextInput::make('customer_id')
                    ->required()
                    ->numeric(),
                TextInput::make('plate_number')
                    ->required(),
                TextInput::make('brand')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                Select::make('type')
                    ->options(['Motor' => 'Motor', 'Mobil' => 'Mobil'])
                    ->default('Motor')
                    ->required(),
                TextInput::make('color'),
                TextInput::make('year'),
            ]);
    }
}
