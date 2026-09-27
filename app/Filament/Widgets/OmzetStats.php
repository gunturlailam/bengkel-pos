<?php

namespace App\Filament\Widgets;

use App\Models\Part;
use App\Models\WorkOrder;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use App\Models\User;
use Override;

class OmzetStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    #[Override]
    public static function canView(): bool
    {
        return User::current()?->isAdmin() ?? false;
    }

    protected function getStats(): array
    {
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            now()->startOfMonth();

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        $diffInDays = $startDate->diffInDays($endDate) + 1;

        $revenue = WorkOrder::whereBetween('created_at', [$startDate, $endDate])->sum('total');
        $transactions = WorkOrder::whereBetween('created_at', [$startDate, $endDate])->count();
        $lowStock = Part::whereColumn('stock', '<=', 'min_stock')->count();

        return [
            Stat::make('Total Omzet', 'Rp ' . number_format($revenue, 0, ', ', '.'))
                ->description('Pendapatan periode ' . $diffInDays . ' hari')
                ->description('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Transaksi', $transactions)
                ->description('Nota yang diterbitkan')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Stok Menipis', $lowStock)
                ->description('Perlu restock segera')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStock > 0 ? 'danger' : 'gray'),
        ];
    }
}
