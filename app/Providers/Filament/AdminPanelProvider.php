<?php

namespace App\Providers\Filament;

use App\Filament\Pages\EditProfile;
use App\Filament\Widgets\CatalogStatsOverview;
use App\Filament\Widgets\ProductCategoryChart;
use App\Filament\Widgets\ProductStatusChart;
use App\Filament\Widgets\RecentProductsTable;
use App\Models\Setting;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile(EditProfile::class, isSimple: false)
            ->brandName(Setting::storeName())
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString('<style>@font-face{font-family:"Material Symbols Rounded";font-style:normal;font-weight:100 700;font-display:swap;src:url("/fonts/material-symbols-rounded.woff2?v=20260810-3") format("woff2");}.material-symbols-rounded{font-family:"Material Symbols Rounded";font-feature-settings:"liga";-webkit-font-smoothing:antialiased;}</style><style>.fi-fo-rich-editor-main > .fi-fo-rich-editor-content, .fi-fo-rich-editor-main > .fi-fo-rich-editor-content [contenteditable="true"] { min-height: 220px; } @media (max-width: 640px) { .fi-fo-rich-editor-main > .fi-fo-rich-editor-content, .fi-fo-rich-editor-main > .fi-fo-rich-editor-content [contenteditable="true"] { min-height: 180px; } }</style>'),
            )
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigationItems([
                NavigationItem::make('Profil')
                    ->key('admin-profile')
                    ->group('Pengaturan')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->sort(2)
                    ->url(fn (): ?string => Filament::getProfileUrl())
                    ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.auth.profile')),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                CatalogStatsOverview::class,
                ProductCategoryChart::class,
                ProductStatusChart::class,
                RecentProductsTable::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
