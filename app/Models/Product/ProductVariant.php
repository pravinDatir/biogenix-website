<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'variant_name',
        'attributes_json',
        'min_order_quantity',
        'max_order_quantity',
        'model_number',
        'catalog_number',
        'stock_quantity',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'product_id' => 'integer',
            'attributes_json' => 'array',
            'min_order_quantity' => 'integer',
            'max_order_quantity' => 'integer',
            'stock_quantity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    // This links the variant to its parent product.
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // This loads all prices for the variant.
    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class, 'product_variant_id');
    }

    // This loads legacy attribute rows for the variant.
    public function attributes(): HasMany
    {
        return $this->hasMany(VariantAttribute::class, 'product_variant_id');
    }
}
