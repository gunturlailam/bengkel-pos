<?php

namespace App\Filament\Widgets;

use App\Models\WorkOrder;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class GrafikPenjualan extends ChartWidget
{
    protected ?string $heading = 'Omzet 7 Hari Terakhir';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days->put($date->format('Y-m-d'), $date->format('d M'));
        }

        $revenues = WorkOrder::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        $data = $days->map(fn($label, $date) => $revenues[$date] ?? 0)->values();
        $labels = $days->values();

        return [
            'datasets' => [
                [
                    'label' => 'Omzet (Rp)',
                    'date' => $data,
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
