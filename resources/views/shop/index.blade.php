<x-guest-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center mb-8">Our Shop</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($products as $product)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <a href="#">
                        <img class="h-48 w-full object-cover" 
                             src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}" 
                             alt="{{ $product->name }}">
                    </a>
                    <div class="p-4">
                        <h2 class="font-bold text-lg">{{ $product->name }}</h2>
                        <p class="text-gray-700 mt-2">{{ $product->description }}</p>
                        <p class="text-gray-900 font-semibold mt-2">₱{{ number_format($product->price, 2) }}</p>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">No products found.</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $products->links() }}
    </div>
    </div>
</x-guest-layout>
