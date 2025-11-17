<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Darryldecode\Cart\Facades\Cart; // <-- Correct Facade

class CartController extends Controller
{
    public function index()
    {
        // You can pass cart data to the view if needed
        $cartItems = Cart::getContent();           // all items
        $cartTotal = Cart::getTotal();             // total price
        $cartCount = Cart::getTotalQuantity();     // total quantity

        return view('cart.index', compact('cartItems', 'cartTotal', 'cartCount'));
    }

    public function store(Request $request)
    {
        $product = Product::findOrFail($request->input('product_id'));
        $quantity = $request->input('quantity', 1);

        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Not enough stock available!');
        }

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantity,
            'attributes' => [
                'image' => 'default.jpg'
            ]
        ]);

        return redirect()->route('shop.index')->with('success', 'Product added to cart!');
    }

    public function destroy($id)
    {
        Cart::remove($id);

        return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
    }

    public function clear()
    {
        Cart::clear();

        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }
}
