<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'product_id',
        'category_id',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * モデルのブートメソッド
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->product_id)) {
                $lastProduct = static::orderBy('product_id', 'desc')->first();
                $product->product_id = $lastProduct ? $lastProduct->product_id + 1 : 1;
            }
        });
    }

    /**
     * カテゴリとのリレーション
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 在庫が少ないかどうか
     */
    public function isLowStock(int $threshold = 10): bool
    {
        return $this->stock_quantity <= $threshold;
    }

    /**
     * 在庫があるかどうか
     */
    public function inStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    /**
     * フォーマットされた価格
     */
    public function getFormattedPriceAttribute(): string
    {
        return '¥' . number_format($this->price);
    }
}
