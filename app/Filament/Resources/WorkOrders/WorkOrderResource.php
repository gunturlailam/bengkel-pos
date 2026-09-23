<?php

namespace App\Filament\Resources\WorkOrders;

use App\Filament\Resources\WorkOrders\Pages\ListWorkOrders;
use App\Filament\Resources\WorkOrders\Pages\ViewWorkOrder;
use App\Models\WorkOrder;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class WorkOrderResource extends Resource
{
    protected static ?string $model = WorkOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Riwayat Transaksi';
    protected static ?string $modelLabel = 'Transaksi';
    protected static ?string $pluralModelLabel = 'Riwayat Transaksi';
    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 2;

    public static function statusOptions(): array
    {
        return [
            'pending' => 'Menunggu',
            'process' => 'Dikerjakan',
            'done' => 'Selesai',
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('No. Invoice')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('vehicle.plate_number')
                    ->label('Kendaraan'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => self::statusOptions()[$state] ?? $state)
                    ->color(fn(string $state) => match ($state) {
                        'pending' => 'warning',
                        'process' => 'info',
                        'done' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make()->label('Lihat'),
                Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Select::make('status')
                            ->label('Status Pengerjaan')
                            ->options(self::statusOptions())
                            ->default(fn(WorkOrder $record) => $record->status)
                            ->required(),
                    ])
                    ->action(fn(WorkOrder $record, array $data) => $record->update($data)),
            ])
            ->toolbarActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('invoice_number')->label('No. Invoice'),
            TextEntry::make('created_at')->label('Tanggal')->dateTime('d/m/Y H:i'),
            TextEntry::make('customer.name')->label('Pelanggan'),
            TextEntry::make('vehicle.plate_number')->label('Kendaraan'),
            TextEntry::make('status')
                ->label('Status')
                ->badge()
                ->formatStateUsing(fn(string $state) => self::statusOptions()[$state] ?? $state),
            TextEntry::make('subtotal')->label('Subtotal')->money('IDR', locale: 'id'),
            TextEntry::make('discount')->label('Diskon')->money('IDR', locale: 'id'),
            TextEntry::make('total')->label('Total Bayar')->money('IDR', locale: 'id'),
            RepeatableEntry::make('items')
                ->label('Detail Item (Jasa & Sparepart)')
                ->schema([
                    TextEntry::make('name')->label('Nama'),
                    TextEntry::make('qty')->label('Qty'),
                    TextEntry::make('price')->label('Harga')->money('IDR', locale: 'id'),
                    TextEntry::make('subtotal')->label('Subtotal')->money('IDR', locale: 'id'),
                ])
                ->columns(4),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkOrders::route('/'),
            'view' => ViewWorkOrder::route('/{record}'),
        ];
    }
}
