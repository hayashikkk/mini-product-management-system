<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * 製品とのリレーション
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * アクティブな製品のみ
     */
    public function activeProducts(): HasMany
    {
        return $this->products()->where('is_active', true);
    }
}
