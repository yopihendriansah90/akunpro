<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $setting = Setting::current();

        return view('index', [
            'products' => $this->catalogProducts(),
            'categories' => Cache::remember('kasirakun-categories-v1', 300, fn () => Category::ordered()->get(['id', 'name', 'icon'])),
            'testimonials' => Cache::remember('kasirakun-testimonials-v1', 300, fn () => Testimonial::active()->ordered()->get()),
            'storeName' => $setting->store_name ?: config('app.name'),
            'whatsappNumber' => $setting->whatsapp_number ?: config('whatsapp.number'),
        ]);
    }

    public function show(int|string $product)
    {
        $product = Product::query()
            ->with([
                'category:id,name,icon',
                'media' => fn ($query) => $query->where('collection_name', 'images'),
            ])
            ->findOrFail($product);

        abort_unless($product->available, 404);

        $catalog = collect($this->catalogProducts());
        $setting = Setting::current();
        $related = $catalog
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(10)
            ->values();

        return view('product.show', [
            'product' => $product,
            'related' => $related,
            'cartProducts' => $catalog,
            'storeName' => $setting->store_name ?: config('app.name'),
            'whatsappNumber' => $setting->whatsapp_number ?: config('whatsapp.number'),
        ]);
    }

    /**
     * Cache the already-mapped storefront payload so public requests do not
     * repeat relation and media lookups for every visitor.
     *
     * @return array<int, array<string, mixed>>
     */
    private function catalogProducts(): array
    {
        return Cache::remember(Product::CATALOG_CACHE_KEY, 60, function (): array {
            return Product::query()
                ->select([
                    'id', 'category_id', 'name', 'description', 'price',
                    'original_price', 'duration', 'warranty', 'icon',
                    'rating', 'badge',
                ])
                ->active()
                ->with([
                    'category:id,name,icon',
                    'media' => fn ($query) => $query->where('collection_name', 'images'),
                ])
                ->ordered()
                ->get()
                ->map(fn (Product $product): array => $this->productData($product))
                ->all();
        });
    }

    private function productData(Product $product): array
    {
        return [
            'id' => $product->id,
            'category_id' => $product->category_id,
            'name' => $product->name,
            'category' => $product->category->name,
            'price' => $product->price,
            'original_price' => $product->original_price,
            'duration' => $product->duration,
            'warranty' => $product->warranty,
            'icon' => $product->icon,
            'rating' => $product->rating,
            'badge' => $product->badge,
            'description' => Str::limit(strip_tags($product->description), 240),
            'image' => $product->getImageUrl(),
        ];
    }
}
