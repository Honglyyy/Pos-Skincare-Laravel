<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Product;

class Category extends Model
{
    protected $fillable = ['name'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'products_categories');
    }

    protected static function booted(): void
    {
        static::deleting(function (Category $category) {
            $category->products->each->delete();
        });
    }
}
