<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'sort'];

    protected static function booted(): void
    {
        static::saved(function (): void {
            Cache::forget(Product::CATALOG_CACHE_KEY);
            Cache::forget('kasirakun-categories-v1');
        });

        static::deleted(function (): void {
            Cache::forget(Product::CATALOG_CACHE_KEY);
            Cache::forget('kasirakun-categories-v1');
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
