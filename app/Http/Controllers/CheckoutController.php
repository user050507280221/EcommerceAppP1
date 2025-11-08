<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{
    public function create()
    {
        if (Cart::count() == 0) {
            return redirect()->route('dashboard')->with('error', 'Your cart is empty.');
        }

        return view('checkout.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Create the Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'shipping_address' => $request->shipping_address,
                'total' => Cart::total(2, '.', ''),
                'status' => 'pending',
            ]);
            // 2. Create the Order Items
            foreach (Cart::content() as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->id,
                    'quantity' => $cartItem->qty,
                    'price' => $cartItem->price,
                ]);
            }
             // 3. Clear the cart
            Cart::destroy();
        });
            // 4. Redirect to a "success" page
        return redirect()->route('checkout.success');
    }

    public function success()
    {
        return view('checkout.success');
    }
}
