<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'image',
        'brand_id',
        'cost',
        'barcode',
        'description',
        'expiration_date',
    ];

    public function orderDetails(): HasMany{
        return $this->hasMany(OrderDetail::class);
    }

    public function brand(): BelongsTo{
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany{
        return $this->belongsToMany(Category::class,'products_categories');
    }

    public function suppliers(): BelongsToMany{
        return $this->belongsToMany(Supplier::class, 'products_suppliers');
    }

    public function inventories(): HasMany{
        return $this->hasMany(Inventory::class);
    }
}
