<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Media::saved(function (Media $media): void {
            if ($media->model_type === Product::class) {
                Cache::forget(Product::CATALOG_CACHE_KEY);
            }
        });

        Media::deleted(function (Media $media): void {
            if ($media->model_type === Product::class) {
                Cache::forget(Product::CATALOG_CACHE_KEY);
            }
        });
    }
}
