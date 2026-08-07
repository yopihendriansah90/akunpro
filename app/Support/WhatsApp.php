<?php

namespace App\Support;

use App\Models\Product;
use App\Models\Setting;

class WhatsApp
{
    public static function number(): string
    {
        return Setting::whatsappNumber();
    }

    public static function formatRupiah(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    public static function directMessage(Product $product): string
    {
        return implode("\n", [
            'Halo, aku mau Beli akun pro',
            '*' . $product->name . '*',
            '- Masa aktif ' . $product->duration,
            '- garansi ' . $product->warranty,
            '- ' . static::formatRupiah($product->price),
            '',
            'Apakah masih tersedia?',
        ]);
    }

    public static function link(string $message): string
    {
        return 'https://wa.me/' . static::number() . '?text=' . rawurlencode($message);
    }

    public static function directLink(Product $product): string
    {
        return static::link(static::directMessage($product));
    }
}
