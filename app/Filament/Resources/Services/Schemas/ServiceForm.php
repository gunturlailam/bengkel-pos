<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Jasa Servis')
                    ->required()
                    ->maxLength(255),


                TextInput::make('price')
                    ->label('Tarif Jasa')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp'),

                TextInput::make('duration_minutes')
                    ->label('Estimasi Waktu Pengerjaan')
                    ->numeric()
                    ->default(30)
                    ->suffix('Menit')
                    ->helperText('Perkiraan waktu yang dibutuhkan mekanik.'),

                Toggle::make('is_active')
                    ->label('Status Aktif (Bisa dipilih kasir?)')
                    ->default(true)
                    ->required(),
            ]);
    }
}
