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
            ->map(fn (Product $p) => [
                'id' => $p->id,
                'name' => $p->name,
                'category' => $p->category->name,
                'price' => $p->price,
                'original_price' => $p->original_price,
                'duration' => $p->duration,
                'warranty' => $p->warranty,
                'icon' => $p->icon,
                'rating' => $p->rating,
                'badge' => $p->badge,
                'description' => $p->description,
                'image' => $p->getImageUrl(),
            ]);

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
            ->map(fn (Product $p) => [
                'id' => $p->id,
                'name' => $p->name,
                'category' => $p->category->name,
                'price' => $p->price,
                'original_price' => $p->original_price,
                'duration' => $p->duration,
                'warranty' => $p->warranty,
                'icon' => $p->icon,
                'rating' => $p->rating,
                'badge' => $p->badge,
                'description' => $p->description,
                'image' => $p->getImageUrl(),
            ]);

        return view('product.show', [
            'product' => $product,
            'related' => $related,
            'storeName' => Setting::storeName(),
            'whatsappNumber' => Setting::whatsappNumber(),
        ]);
    }
}
