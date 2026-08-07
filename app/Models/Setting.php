<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['store_name', 'whatsapp_number'];

    public static function current(): static
    {
        return Cache::remember('kasirakun-settings', 3600, function () {
            return static::query()->first() ?? static::create([
                'store_name' => config('app.name'),
                'whatsapp_number' => config('whatsapp.number'),
            ]);
        });
    }

    public static function storeName(): string
    {
        try {
            return static::current()->store_name ?: config('app.name');
        } catch (\Throwable) {
            return config('app.name');
        }
    }

    public static function whatsappNumber(): string
    {
        try {
            return static::current()->whatsapp_number ?: config('whatsapp.number');
        } catch (\Throwable) {
            return config('whatsapp.number');
        }
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('kasirakun-settings'));
    }
}
