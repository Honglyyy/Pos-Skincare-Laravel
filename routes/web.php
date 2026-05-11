<?php

use Illuminate\Support\Facades\Route;
use App\Models\Order;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/invoice/{order}', function ($orderId) {

    $order = Order::with([
        'orderDetails.product',
        'customer',
        'creator'
    ])->findOrFail($orderId);

    return view('/receipts.order', compact('order'));
});