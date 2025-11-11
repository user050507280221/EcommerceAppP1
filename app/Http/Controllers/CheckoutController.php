<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CheckoutController extends Controller
{
    public function index()
    {
        // If cart is empty, redirect back to shop
        if (Cart::getContent()->count() == 0) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        // Get total price of cart
        $cartTotal = Cart::getTotal(); // Darryldecode uses getTotal()
        return view('checkout.index', compact('cartTotal'));
    }

    public function create()
    {
        if (Cart::getContent()->count() == 0) {
            return redirect()->route('dashboard')->with('error', 'Your cart is empty.');
        }

        return view('checkout.create');
    }

    public function store(Request $request)
    {
        // Validate shipping info
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            // Check stock for every item in cart
            foreach (Cart::getContent() as $item) {
                $product = Product::findOrFail($item->id);
                if ($product->stock < $item->quantity) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', 'The product "' . $product->name . '" does not have enough stock.');
                }
            }

            // Create the Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'total_price' => Cart::getTotal(), // total without formatting
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_phone' => $request->shipping_phone,
            ]);

            // Create Order Items and update stock
            foreach (Cart::getContent() as $item) {
                // Attach product to order (pivot table)
                $order->products()->attach($item->id, [
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);

                // Update product stock
                $product = Product::findOrFail($item->id);
                $product->stock -= $item->quantity;
                $product->save();
            }

            DB::commit();

            // Clear the cart
            Cart::clear();

            // Redirect to thank you / dashboard page
            return redirect()->route('dashboard')->with('success', 'Order placed successfully! Thank you for your purchase.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.index')->with('error', 'An error occurred while placing your order. Please try again. Error: ' . $e->getMessage());
        }
    }

    public function success()
    {
        return view('checkout.success');
    }
}
