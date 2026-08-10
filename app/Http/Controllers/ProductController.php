<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Testimonial;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::active()
            ->with('category')
            ->ordered()
            ->get()
            ->map(fn (Product $p) => $this->productData($p));

        return view('index', [
            'products' => $products,
            'categories' => Category::ordered()->get(['id', 'name', 'icon']),
            'testimonials' => Testimonial::active()->ordered()->get(),
            'storeName' => Setting::storeName(),
            'whatsappNumber' => Setting::whatsappNumber(),
        ]);
    }

    public function show(Product $product)
    {
        abort_unless($product->available, 404);

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('category')
            ->ordered()
            ->take(10)
            ->get()
            ->map(fn (Product $p) => $this->productData($p));

        $cartProducts = Product::active()
            ->with('category')
            ->ordered()
            ->get()
            ->map(fn (Product $p) => $this->productData($p));

        return view('product.show', [
            'product' => $product,
            'related' => $related,
            'cartProducts' => $cartProducts,
            'storeName' => Setting::storeName(),
            'whatsappNumber' => Setting::whatsappNumber(),
        ]);
    }

    private function productData(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'category' => $product->category->name,
            'price' => $product->price,
            'original_price' => $product->original_price,
            'duration' => $product->duration,
            'warranty' => $product->warranty,
            'icon' => $product->icon,
            'rating' => $product->rating,
            'badge' => $product->badge,
            'description' => strip_tags($product->description),
            'image' => $product->getImageUrl(),
        ];
    }
}
