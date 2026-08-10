<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;

class ProductStatusChart extends ChartWidget
{
    protected ?string $heading = 'Status Produk';

    protected ?string $description = 'Perbandingan produk aktif dan nonaktif.';

    protected ?string $maxHeight = '280px';

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $counts = Product::query()->selectRaw('available, COUNT(*) as total')->groupBy('available')->pluck('total', 'available');

        return ['labels' => ['Aktif', 'Nonaktif'], 'datasets' => [[
            'data' => [(int) ($counts[1] ?? 0), (int) ($counts[0] ?? 0)],
            'backgroundColor' => ['#10b981', '#94a3b8'],
            'borderColor' => '#18181b',
            'borderWidth' => 3,
        ]]];
    }

    protected function getOptions(): array
    {
        return ['maintainAspectRatio' => false, 'cutout' => '68%', 'plugins' => ['legend' => ['position' => 'bottom']]];
    }
}
