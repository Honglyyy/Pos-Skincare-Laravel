<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'stock_movement',
    ];

    public function product():BelongsTo{
        return $this->belongsTo(Product::class);
    }

    protected static function booted():void{
        static::created(function ($inventory) {

            $product = $inventory->product;

            if ($inventory->stock_movement === 'stock-in') {
                $product->increment('stock', $inventory->quantity);
            }

            if ($inventory->stock_movement === 'stock-out') {
                $product->decrement('stock', $inventory->quantity);
            }
        });
    }
}
