<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function event(Request $request): JsonResponse
    {
        $data = $request->validate([
            'event' => ['required', 'string', 'in:whatsapp_click,cart_checkout'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
        ]);

        // Keep analytics functional when a visitor blocks cookies without
        // storing the raw IP address.
        $visitorId = $request->cookie('kasirakun_visitor')
            ?: hash('sha256', $request->ip().'|'.$request->userAgent());

        AnalyticsEvent::create([
            'event_type' => $data['event'],
            'visitor_hash' => hash('sha256', $visitorId),
            'product_id' => $data['product_id'] ?? null,
        ]);

        return response()->json(['ok' => true], 201);
    }
}
