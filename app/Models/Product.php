<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'original_price',
        'duration',
        'warranty',
        'icon',
        'rating',
        'badge',
        'available',
        'sort',
    ];

    protected $casts = [
        'price' => 'integer',
        'original_price' => 'integer',
        'rating' => 'integer',
        'available' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('available', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10);

        $this->addMediaConversion('card')
            ->width(600)
            ->height(600)
            ->sharpen(5);
    }

    public function getImageUrl(string $conversion = 'card'): ?string
    {
        $media = $this->getFirstMedia('images');

        return $media ? $media->getAvailableUrl([$conversion]) : null;
    }
}
