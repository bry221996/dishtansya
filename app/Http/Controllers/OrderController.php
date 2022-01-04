<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric|exists:products,id',
            'quantity' => 'required|numeric'
        ]);

        $product = Product::find($request->product_id);

        if ($product->available_stock < $request->quantity) {

            return response()
                ->json([
                    'message' => __('messages.order.create.unavailable_stock')
                ], 400);
        }

        $product->decrement('available_stock', $request->quantity);

        return response()
            ->json(
                ['message' => __('messages.order.create.success')],
                201
            );
    }
}
