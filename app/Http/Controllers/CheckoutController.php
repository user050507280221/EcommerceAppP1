<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
 // ✅ Use the class directly
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;

class CheckoutController extends Controller
{
    public function index()
    {
        if (Cart::count() == 0) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $cartTotal = Cart::total();
        return view('checkout.index', compact('cartTotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            foreach (Cart::content() as $item) {
                $product = Product::findOrFail($item->id);
                if ($product->stock < $item->qty) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', 'The product "' . $product->name . '" does not have enough stock.');
                }
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'total_price' => Cart::total(2, '.', ''),
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_phone' => $request->shipping_phone,
            ]);

            foreach (Cart::content() as $item) {
                $order->products()->attach($item->id, [
                    'quantity' => $item->qty,
                    'price' => $item->price,
                ]);

                $product = Product::findOrFail($item->id);
                $product->stock -= $item->qty;
                $product->save();
            }

            DB::commit();

            // Clear the cart safely
            Cart::destroy();

            return redirect()->route('dashboard')->with('success', 'Order placed successfully! Thank you for your purchase.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.index')
                ->with('error', 'An error occurred while placing your order. Please try again. Error: ' . $e->getMessage());
        }
    }
}
