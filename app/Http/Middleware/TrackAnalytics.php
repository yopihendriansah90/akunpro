<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class TrackAnalytics
{
    public function handle(Request $request, Closure $next): Response
    {
        $visitorId = $request->cookie('kasirakun_visitor') ?: Str::random(40);
        $visitorHash = hash('sha256', $visitorId);
        $productId = $this->productId($request);

        $response = $next($request);

        if ($request->isMethod('GET') && $request->acceptsHtml() && $response->isSuccessful()) {
            PageView::create([
                'visitor_hash' => $visitorHash,
                'path' => Str::limit($request->path(), 255, ''),
                'route_name' => $request->route()?->getName(),
                'product_id' => $productId,
            ]);
        }

        if (! $request->hasCookie('kasirakun_visitor')) {
            $response->headers->setCookie(Cookie::create(
                'kasirakun_visitor',
                $visitorId,
                now()->addYear(),
                '/',
                null,
                $request->isSecure(),
                true,
                false,
                Cookie::SAMESITE_LAX,
            ));
        }

        return $response;
    }

    private function productId(Request $request): ?int
    {
        if (! $request->routeIs('product.show')) {
            return null;
        }

        $value = $request->route('product');

        return is_numeric($value) ? (int) $value : null;
    }
}
