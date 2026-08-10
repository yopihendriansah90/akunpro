<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\TrackAnalytics;
use Illuminate\Support\Facades\Route;

Route::middleware(TrackAnalytics::class)->group(function (): void {
    Route::get('/', [ProductController::class, 'index'])->name('home');
    Route::get('/produk/{product}', [ProductController::class, 'show'])->name('product.show');
});

Route::post('/analytics/events', [AnalyticsController::class, 'event'])
    ->middleware('throttle:60,1')
    ->name('analytics.events');
