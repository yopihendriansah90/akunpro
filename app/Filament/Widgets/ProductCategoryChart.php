<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class ProductCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Produk per Kategori';

    protected ?string $description = 'Kategori dengan jumlah produk terbanyak.';

    protected ?string $maxHeight = '280px';

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $categories = Category::query()->select(['id', 'name'])->withCount('products')->orderByDesc('products_count')->limit(8)->get();

        return [
            'labels' => $categories->pluck('name')->all(),
            'datasets' => [[
                'label' => 'Produk',
                'data' => $categories->pluck('products_count')->map(fn ($count): int => (int) $count)->all(),
                'backgroundColor' => '#f59e0b',
                'borderRadius' => 5,
                'maxBarThickness' => 32,
            ]],
        ];
    }

    protected function getOptions(): array
    {
        return ['maintainAspectRatio' => false, 'plugins' => ['legend' => ['display' => false]], 'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]]];
    }
}
