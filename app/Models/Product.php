<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'compare_price',
        'sku',
        'stock_quantity',
        'is_active',
        'is_featured',
        'image_url',
        'images',
        'tags',
        'weight',
        'dimensions',
        'brand',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images' => 'array',
            'tags' => 'array',
            'dimensions' => 'array',
        ];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function hasDiscount(): bool
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }
}
