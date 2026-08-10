<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'role', 'rating', 'text', 'available', 'sort'];

    protected $casts = [
        'rating' => 'integer',
        'available' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('kasirakun-testimonials-v1'));
        static::deleted(fn () => Cache::forget('kasirakun-testimonials-v1'));
    }

    public function scopeActive($query)
    {
        return $query->where('available', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
