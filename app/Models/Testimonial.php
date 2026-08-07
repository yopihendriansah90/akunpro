<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'role', 'rating', 'text', 'available', 'sort'];

    protected $casts = [
        'rating' => 'integer',
        'available' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('available', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
