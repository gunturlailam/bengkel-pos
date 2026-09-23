<?php

namespace App\Filament\Resources\Parts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PartsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable(),
                TextColumn::make('sku')
                    ->label('Kode Barang')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama Sparepart')
                    ->searchable(),
                TextColumn::make('buy_price')
                    ->label('Harga Beli')
                    ->money('IDR', locale: 'id'),
                TextColumn::make('sell_price')
                    ->label('Harga Jual')
                    ->money('IDR', locale: 'id'),
                TextColumn::make('stock')
                    ->label('Stok')
                    ->badge()
                    ->color(fn($record) => $record->stock <= $record->min_stock ? 'danger' : 'success'),
                TextColumn::make('min_stock')
                    ->label('Stok Minimum'),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
