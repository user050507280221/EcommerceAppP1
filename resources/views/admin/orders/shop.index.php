<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }} #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Success message -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Customer Info -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Customer Information</h3>
                    <p><strong>Name:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                </div>

                <!-- Products Table -->
                <div class="mb-6 overflow-x-auto">
                    <h3 class="text-lg font-semibold mb-2">Products</h3>
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border-b text-left">Product</th>
                                <th class="py-2 px-4 border-b text-left">Quantity</th>
                                <th class="py-2 px-4 border-b text-left">Price</th>
                                <th class="py-2 px-4 border-b text-left">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b">{{ $product->name }}</td>
                                    <td class="py-2 px-4 border-b">{{ $product->pivot->quantity }}</td>
                                    <td class="py-2 px-4 border-b">${{ number_format($product->price, 2) }}</td>
                                    <td class="py-2 px-4 border-b">${{ number_format($product->price * $product->pivot->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Total & Status -->
                <div class="mb-6 flex justify-between items-center">
                    <p class="text-lg font-semibold">Total: ${{ number_format($order->total_price, 2) }}</p>

                    <!-- Update Status Form -->
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PUT')
                        <label for="status" class="font-medium">Status:</label>
                        <select name="status" id="status" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(['pending','processing','completed','shipped','cancelled'] as $status)
                                <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        <x-primary-button>Update</x-primary-button>
                    </form>
                </div>

                <!-- Back Button -->
                <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-900">
                    &larr; Back to Orders
                </a>

            </div>
        </div>
    </div>
</x-app-layout>