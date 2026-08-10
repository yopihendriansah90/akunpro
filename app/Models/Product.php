<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Forms\Components\RichEditor\Models\Concerns\InteractsWithRichContent;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, InteractsWithRichContent;

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

    protected function setUpRichContent(): void
    {
        $this->registerRichContent('description');
    }

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
            ->useDisk(config('media-library.disk_name', 'public'))
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('card')
            ->width(600)
            ->height(600)
            ->sharpen(5)
            ->nonQueued();
    }

    public function getImageUrl(string $conversion = 'card'): ?string
    {
        $media = $this->getFirstMedia('images');

        if (! $media) {
            return null;
        }

        $path = $media->getAvailablePathRelativeToRoot(
            $conversion !== '' && $media->hasGeneratedConversion($conversion) ? [$conversion] : []
        );

        // Keep local image URLs relative so they work whether the app is
        // opened through localhost, 127.0.0.1, or another local hostname.
        if ($media->disk === 'public') {
            return '/storage/' . implode('/', array_map(rawurlencode(...), explode('/', $path)));
        }

        return $media->getAvailableUrl([$conversion]);
    }
}
