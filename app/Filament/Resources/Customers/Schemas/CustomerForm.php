<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Pelanggan')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('Nomor Telepon / WA')
                    ->tel()
                    ->maxLength(20),


                Textarea::make('address')
                    ->label('Alamat Lengkap')
                    ->rows(3)
                    ->columnSpanFull(), // Biar alamat full satu baris ke kanan
            ]);
    }
}
