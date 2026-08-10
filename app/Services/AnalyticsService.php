<?php

namespace App\Services;

use App\Models\AnalyticsEvent;
use App\Models\PageView;
use App\Models\Product;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /** @return array<string, mixed> */
    public function dashboard(CarbonImmutable $start, CarbonImmutable $end): array
    {
        $from = $start->startOfDay();
        $to = $end->endOfDay();
        $views = PageView::query()->whereBetween('created_at', [$from, $to]);
        $events = AnalyticsEvent::query()->whereBetween('created_at', [$from, $to]);

        $summary = [
            'views' => (clone $views)->count(),
            'unique_visitors' => (clone $views)->distinct('visitor_hash')->count('visitor_hash'),
            'product_views' => (clone $views)->whereNotNull('product_id')->count(),
            'whatsapp_clicks' => (clone $events)->where('event_type', 'whatsapp_click')->count(),
            'online' => PageView::query()->where('created_at', '>=', now()->subMinutes(5))->distinct('visitor_hash')->count('visitor_hash'),
        ];

        $daily = (clone $views)
            ->selectRaw('DATE(created_at) as period, COUNT(*) as total')
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period');

        $monthlyStart = now()->startOfMonth()->subMonths(11);
        $monthlyEnd = now()->endOfMonth();
        $monthly = PageView::query()
            ->whereBetween('created_at', [$monthlyStart, $monthlyEnd])
            ->selectRaw($this->monthExpression().' as period, COUNT(*) as total')
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period');

        $topProductViews = (clone $views)
            ->whereNotNull('product_id')
            ->selectRaw('product_id, COUNT(*) as total')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->limit(8)
            ->get();
        $products = Product::query()->whereIn('id', $topProductViews->pluck('product_id'))->pluck('name', 'id');

        $eventCounts = (clone $events)
            ->selectRaw('event_type, COUNT(*) as total')
            ->groupBy('event_type')
            ->pluck('total', 'event_type');

        return [
            'summary' => $summary,
            'daily' => $this->fillDailyPeriods($start, $end, $daily),
            'monthly' => $this->fillMonthlyPeriods(
                CarbonImmutable::parse($monthlyStart),
                CarbonImmutable::parse($monthlyEnd),
                $monthly,
            ),
            'top_products' => $topProductViews->map(fn ($row): array => [
                'name' => $products[$row->product_id] ?? 'Produk dihapus',
                'total' => (int) $row->total,
            ])->values()->all(),
            'events' => $eventCounts->map(fn ($total): int => (int) $total)->all(),
        ];
    }

    private function monthExpression(): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m', created_at)",
            'pgsql' => "TO_CHAR(created_at, 'YYYY-MM')",
            default => "DATE_FORMAT(created_at, '%Y-%m')",
        };
    }

    /** @return array<int, array{label: string, total: int}> */
    private function fillDailyPeriods(CarbonImmutable $start, CarbonImmutable $end, $values): array
    {
        $periods = [];
        for ($date = $start; $date->lte($end); $date = $date->addDay()) {
            $key = $date->format('Y-m-d');
            $periods[] = ['label' => $date->format('d M'), 'total' => (int) ($values[$key] ?? 0)];
        }

        return $periods;
    }

    /** @return array<int, array{label: string, total: int}> */
    private function fillMonthlyPeriods(CarbonImmutable $start, CarbonImmutable $end, $values): array
    {
        $periods = [];
        $date = $start->startOfMonth();
        $lastMonth = $end->startOfMonth();
        while ($date->lte($lastMonth)) {
            $key = $date->format('Y-m');
            $periods[] = ['label' => $date->format('M Y'), 'total' => (int) ($values[$key] ?? 0)];
            $date = $date->addMonth();
        }

        return $periods;
    }
}
