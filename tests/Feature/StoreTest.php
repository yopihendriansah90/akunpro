<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_admin_requires_login(): void
    {
        $this->get('/admin')->assertRedirect();
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
