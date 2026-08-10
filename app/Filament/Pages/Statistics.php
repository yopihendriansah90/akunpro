<?php

namespace App\Filament\Pages;

use App\Services\AnalyticsService;
use Carbon\CarbonImmutable;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class Statistics extends Page
{
    protected string $view = 'filament.pages.statistics';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Statistik';

    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 1;

    public string $startDate;

    public string $endDate;

    /** @var array<string, mixed> */
    public array $statistics = [];

    public function mount(AnalyticsService $analytics): void
    {
        $this->startDate = now()->subDays(29)->toDateString();
        $this->endDate = now()->toDateString();
        $this->refreshStatistics($analytics);
    }

    public function getTitle(): string|Htmlable
    {
        return 'Statistik Website';
    }

    public function applyFilters(): void
    {
        $this->validate([
            'startDate' => ['required', 'date'],
            'endDate' => ['required', 'date', 'after_or_equal:startDate'],
        ]);

        $this->refreshStatistics(app(AnalyticsService::class));
    }

    public function setPreset(string $preset): void
    {
        $today = now();

        [$start, $end] = match ($preset) {
            'today' => [$today, $today],
            'yesterday' => [$today->copy()->subDay(), $today->copy()->subDay()],
            '7days' => [$today->copy()->subDays(6), $today],
            '30days' => [$today->copy()->subDays(29), $today],
            'month' => [$today->copy()->startOfMonth(), $today],
            'lastMonth' => [
                $today->copy()->subMonthNoOverflow()->startOfMonth(),
                $today->copy()->subMonthNoOverflow()->endOfMonth(),
            ],
            default => [$today->copy()->subDays(29), $today],
        };

        $this->startDate = $start->toDateString();
        $this->endDate = $end->toDateString();
        $this->refreshStatistics(app(AnalyticsService::class));
    }

    private function refreshStatistics(AnalyticsService $analytics): void
    {
        $this->statistics = $analytics->dashboard(
            CarbonImmutable::parse($this->startDate),
            CarbonImmutable::parse($this->endDate),
        );
    }
}
