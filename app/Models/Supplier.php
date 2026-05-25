<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Product;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'products_suppliers');
    }

    protected static function booted(): void
    {
        static::deleting(function (Supplier $supplier) {
            $supplier->products->each->delete();
        });
    }
}
