<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CatalogStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Katalog';

    protected ?string $description = 'Pantau kesiapan katalog dan konten yang tampil di website.';

    protected function getStats(): array
    {
        $stats = Product::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN available = 1 THEN 1 ELSE 0 END) as active')
            ->selectRaw('SUM(CASE WHEN available = 0 THEN 1 ELSE 0 END) as inactive')
            ->first();
        $total = (int) ($stats?->total ?? 0);
        $active = (int) ($stats?->active ?? 0);
        $inactive = (int) ($stats?->inactive ?? 0);
        $missingImages = Product::query()->whereDoesntHave('media', fn ($query) => $query->where('collection_name', 'images'))->count();

        return [
            Stat::make('Produk Aktif', $active)->description("dari {$total} produk")->descriptionIcon('heroicon-m-check-circle')->color('success')->icon('heroicon-o-shopping-bag')->url(ProductResource::getUrl('index')),
            Stat::make('Produk Nonaktif', $inactive)->description('tidak tampil di katalog')->descriptionIcon('heroicon-m-eye-slash')->color($inactive > 0 ? 'warning' : 'success')->icon('heroicon-o-eye-slash')->url(ProductResource::getUrl('index')),
            Stat::make('Kategori', Category::query()->count())->description('kelompok produk tersedia')->descriptionIcon('heroicon-m-tag')->color('info')->icon('heroicon-o-tag'),
            Stat::make('Testimoni Aktif', Testimonial::query()->active()->count())->description('testimoni yang ditampilkan')->descriptionIcon('heroicon-m-chat-bubble-left-right')->color('warning')->icon('heroicon-o-chat-bubble-left-right'),
            Stat::make('Data Perlu Dilengkapi', $missingImages)->description($missingImages > 0 ? 'produk tanpa foto' : 'semua produk punya foto')->descriptionIcon($missingImages > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')->color($missingImages > 0 ? 'danger' : 'success')->icon('heroicon-o-photo')->url(ProductResource::getUrl('index')),
        ];
    }
}
