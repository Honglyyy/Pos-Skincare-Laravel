<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'total_price',
        'order_date',
        'discount',
        'discount_amount',
        'total_payment',
        'status',
        'created_by'
    ];

    public function customer():BelongsTo{
        return $this->belongsTo(Customer::class);
    }

    public function orderDetails(): HasMany{
        return $this->hasMany(OrderDetail::class);
    }

    protected static function booted()
    {
        static::updated(function ($order) {

            $originalStatus = $order->getOriginal('status');

            if($order->status == 'completed'){
                foreach($order->orderDetails as $orderDetail){
                    $product = $orderDetail->product;

                    if($product){
                        $product -> decrement('stock', $orderDetail->quantity);
                    }
                }
            }

            if(($order->status == 'cancelled' || $order->status == 'processing' || $order->status == 'new') && $originalStatus == 'completed'){
                foreach($order->orderDetails as $orderDetail){
                    $product = $orderDetail->product;

                    if($product){
                        $product -> increment('stock', $orderDetail->quantity);
                    }
                }
            }
        });
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
