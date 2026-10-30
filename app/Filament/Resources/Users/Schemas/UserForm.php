<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Select::make('role')
                    ->label('Peran (Role)')
                    ->options([
                        'admin' => 'Admin (Akses Penuh)',
                        'kasir' => 'Kasir (POS & Transaksi)',
                        'mekanik' => 'Mekanik (Pengerjaan & Status)',
                    ])
                    ->default('kasir')
                    ->required(),

                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->revealable()
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->helperText(fn (string $operation): ?string => $operation === 'edit' ? 'Kosongkan jika tidak ingin mengubah kata sandi.' : null)
                    ->maxLength(255),
            ]);
    }
}
