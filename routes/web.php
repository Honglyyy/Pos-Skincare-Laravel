<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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

Route::get('/private-image/{path}', function ($path) {
    $path = rawurldecode($path);

    if (! Storage::disk('private')->exists($path)) {
        abort(404);
    }

    return response()->file(Storage::disk('private')->path($path));
})->where('path', '.*')->name('private-image');