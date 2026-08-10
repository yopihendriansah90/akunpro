<?php

namespace Tests\Feature;

use App\Filament\Widgets\CatalogStatsOverview;
use App\Filament\Widgets\ProductCategoryChart;
use App\Filament\Widgets\ProductStatusChart;
use App\Filament\Widgets\RecentProductsTable;
use App\Models\AnalyticsEvent;
use App\Models\Category;
use App\Models\PageView;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Services\AnalyticsService;
use Carbon\CarbonImmutable;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_catalog(): void
    {
        Setting::create(['store_name' => 'KasirAkun', 'whatsapp_number' => '6283116545674']);
        $category = Category::create(['name' => 'AI Chat', 'icon' => 'chat', 'sort' => 1]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Gemini Pro',
            'description' => 'Akses penuh Gemini Pro.',
            'price' => 30000,
            'original_price' => 45000,
            'duration' => '3 bulan',
            'warranty' => '1 bulan',
            'icon' => 'auto_awesome',
            'rating' => 5,
            'available' => true,
            'sort' => 1,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('KasirAkun')
            ->assertSee('Gemini Pro')
            ->assertSee('window.KASIRAKUN');
    }

    public function test_home_catalog_loads_media_without_n_plus_one_queries(): void
    {
        $category = Category::create(['name' => 'AI Chat', 'icon' => 'chat', 'sort' => 1]);

        foreach (range(1, 3) as $index) {
            Product::create([
                'category_id' => $category->id,
                'name' => "Product {$index}",
                'description' => 'Deskripsi.',
                'price' => 30000,
                'duration' => '1 bulan',
                'warranty' => '1 bulan',
                'icon' => 'apps',
                'rating' => 5,
                'available' => true,
                'sort' => $index,
            ]);
        }

        Cache::forget(Product::CATALOG_CACHE_KEY);
        DB::flushQueryLog();
        DB::enableQueryLog();

        $this->get('/')->assertOk();

        $mediaQueries = collect(DB::getQueryLog())
            ->filter(fn (array $query): bool => (bool) preg_match('/from [`"]media[`"]/', $query['query']));

        $this->assertCount(1, $mediaQueries);
    }

    public function test_storefront_sets_baseline_security_headers(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_storefront_tracks_anonymous_views_and_events(): void
    {
        $response = $this->get('/');

        $response->assertOk()->assertCookie('kasirakun_visitor');
        $this->assertDatabaseCount('page_views', 1);
        $visitorCookie = collect($response->headers->getCookies())
            ->first(fn ($cookie): bool => $cookie->getName() === 'kasirakun_visitor')
            ->getValue();

        $this->withCookie('kasirakun_visitor', $visitorCookie)
            ->postJson('/analytics/events', ['event' => 'whatsapp_click'])
            ->assertCreated()
            ->assertJson(['ok' => true]);

        $this->assertDatabaseHas('analytics_events', ['event_type' => 'whatsapp_click']);
    }

    public function test_analytics_service_returns_filtered_dashboard_data(): void
    {
        PageView::create(['visitor_hash' => hash('sha256', 'one'), 'path' => '/', 'route_name' => 'home']);
        PageView::create(['visitor_hash' => hash('sha256', 'two'), 'path' => '/', 'route_name' => 'home']);
        AnalyticsEvent::create(['visitor_hash' => hash('sha256', 'one'), 'event_type' => 'whatsapp_click']);
        $oldView = PageView::create(['visitor_hash' => hash('sha256', 'old'), 'path' => '/', 'route_name' => 'home']);
        $oldView->created_at = now()->subMonth();
        $oldView->updated_at = $oldView->created_at;
        $oldView->saveQuietly();

        $data = app(AnalyticsService::class)->dashboard(
            CarbonImmutable::today(),
            CarbonImmutable::today(),
        );

        $this->assertSame(2, $data['summary']['views']);
        $this->assertSame(2, $data['summary']['unique_visitors']);
        $this->assertSame(1, $data['summary']['whatsapp_clicks']);
        $this->assertNotEmpty($data['daily']);
        $this->assertCount(12, $data['monthly']);
    }

    public function test_admin_requires_login(): void
    {
        $this->get('/admin')->assertRedirect();
    }

    public function test_admin_dashboard_renders_catalog_widgets(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'dashboard@kasirakun.test',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk();

        $widgets = Filament::getWidgets();

        $this->assertContains(CatalogStatsOverview::class, $widgets);
        $this->assertContains(ProductCategoryChart::class, $widgets);
        $this->assertContains(ProductStatusChart::class, $widgets);
        $this->assertContains(RecentProductsTable::class, $widgets);
    }

    public function test_manage_settings_page_renders_for_authenticated_user(): void
    {
        Setting::create(['store_name' => 'KasirAkun', 'whatsapp_number' => '6283116545674']);
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@kasirakun.test',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user)
            ->get('/admin/manage-settings')
            ->assertOk()
            ->assertSee('Nama Brand / Toko')
            ->assertSee('Nomor WhatsApp Admin');
    }

    public function test_admin_profile_page_renders_for_authenticated_user(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'profile@kasirakun.test',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user)
            ->get('/admin/profile')
            ->assertOk()
            ->assertSee('Nama')
            ->assertSee('Alamat email')
            ->assertSee('Kata sandi baru');
    }

    public function test_product_detail_page_renders_with_related(): void
    {
        Setting::create(['store_name' => 'KasirAkun', 'whatsapp_number' => '6283116545674']);
        $category = Category::create(['name' => 'AI Chat', 'icon' => 'chat', 'sort' => 1]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Gemini Pro',
            'description' => 'Akses penuh Gemini Pro.',
            'price' => 30000,
            'original_price' => 45000,
            'duration' => '3 bulan',
            'warranty' => '1 bulan',
            'icon' => 'auto_awesome',
            'rating' => 5,
            'available' => true,
            'sort' => 1,
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'ChatGPT Plus',
            'description' => 'Asisten AI.',
            'price' => 250000,
            'duration' => '1 bulan',
            'warranty' => '1 bulan',
            'icon' => 'smart_toy',
            'rating' => 5,
            'available' => true,
            'sort' => 2,
        ]);

        $this->get("/produk/{$product->id}")
            ->assertOk()
            ->assertSee('Gemini Pro')
            ->assertSee('Produk Serupa')
            ->assertSee('ChatGPT Plus');
    }

    public function test_product_rich_description_is_rendered_and_sanitized(): void
    {
        Setting::create(['store_name' => 'KasirAkun', 'whatsapp_number' => '6283116545674']);
        $category = Category::create(['name' => 'AI Chat', 'icon' => 'chat', 'sort' => 1]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Rich Product',
            'description' => '<p><strong>Fitur utama</strong></p><ul><li>Akses premium</li></ul><script>alert(1)</script>',
            'price' => 30000,
            'duration' => '3 bulan',
            'warranty' => '1 bulan',
            'icon' => 'auto_awesome',
            'rating' => 5,
            'available' => true,
            'sort' => 1,
        ]);

        $this->get("/produk/{$product->id}")
            ->assertOk()
            ->assertSee('<strong>Fitur utama</strong>', false)
            ->assertSee('<ul', false)
            ->assertSee('<li', false)
            ->assertSee('Akses premium')
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_product_media_image_and_conversion(): void
    {
        Setting::create(['store_name' => 'KasirAkun', 'whatsapp_number' => '6283116545674']);
        $category = Category::create(['name' => 'AI Chat', 'icon' => 'chat', 'sort' => 1]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Gemini Pro',
            'description' => 'Deskripsi.',
            'price' => 30000,
            'duration' => '3 bulan',
            'warranty' => '1 bulan',
            'icon' => 'auto_awesome',
            'rating' => 5,
            'available' => true,
            'sort' => 1,
        ]);

        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
        $product->addMediaFromString($png)
            ->usingFileName('foto.png')
            ->toMediaCollection('images');

        $this->assertNotNull($product->getImageUrl());
        $this->assertNotNull($product->getImageUrl('thumb'));
        $this->assertTrue($product->getFirstMedia('images')->hasGeneratedConversion('thumb'));
    }

    public function test_product_media_replacement_and_deletion_are_owned_by_media_library(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'AI Chat', 'icon' => 'chat', 'sort' => 1]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Gemini Pro',
            'description' => 'Deskripsi.',
            'price' => 30000,
            'duration' => '3 bulan',
            'warranty' => '1 bulan',
            'icon' => 'auto_awesome',
            'rating' => 5,
            'available' => true,
            'sort' => 1,
        ]);
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');

        $first = $product->addMediaFromString($png)
            ->usingFileName('first.jpg')
            ->toMediaCollection('images');
        $firstPath = $first->getPathRelativeToRoot();

        $second = $product->addMediaFromString($png)
            ->usingFileName('second.jpg')
            ->toMediaCollection('images');

        $this->assertSame(1, $product->fresh()->getMedia('images')->count());
        $this->assertSame($second->uuid, $product->fresh()->getFirstMedia('images')->uuid);
        Storage::disk('public')->assertMissing($firstPath);

        $product->delete();

        $this->assertDatabaseMissing('media', ['uuid' => $second->uuid]);
        Storage::disk('public')->assertMissing($second->getPathRelativeToRoot());
    }

    public function test_admin_product_create_page_renders(): void
    {
        Setting::create(['store_name' => 'KasirAkun', 'whatsapp_number' => '6283116545674']);
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@kasirakun.test',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user)
            ->get('/admin/products/create')
            ->assertOk()
            ->assertSee('Foto Produk')
            ->assertSee('Harga Jual');
    }
}
