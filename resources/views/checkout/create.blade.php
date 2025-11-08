<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium">Order Summary</h3>
                <h4 class="text-xl font-bold mt-2">Total: ${{ Cart::total() }}</h4>

                <!-- <form action="{{ route('checkout.store') }}" method="POST" class="mt-6">
                    @csrf
                    <div class="mb-4">
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                        <textarea name="shipping_address" id="shipping_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('shipping_address') }}</textarea>
                    </div>
                    <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                        Place Order
                    </button>
                </form> -->

                <!-- Product Form Section -->
                <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST">
                    @csrf
                    @if(isset($product))
                        @method('PUT')
                    @endif

                    <!-- Product Name -->
                    <div class="mb-4">
                        <label for="name" class="block font-medium">Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="w-full border rounded p-2" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block font-medium">Description</label>
                        <textarea name="description" class="w-full border rounded p-2" required>{{ old('description', $product->description ?? '') }}</textarea>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <label for="price" class="block font-medium">Price</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" class="w-full border rounded p-2" required>
                    </div>

                    <!-- Stock -->
                    <div class="mb-4">
                        <label for="stock" class="block font-medium">Stock</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? '') }}" class="w-full border rounded p-2" required>
                    </div>

                    <!-- Category Dropdown -->
                    <div class="mb-4">
                        <label for="category_id" class="block font-medium">Category</label>
                        <select name="category_id" id="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ (isset($product) && $product->category_id == $category->id) || old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        {{ isset($product) ? 'Update Product' : 'Save Product' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
