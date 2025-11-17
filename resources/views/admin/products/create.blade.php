@extends('layouts.admin') {{-- Adjust if your layout file is different --}}

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg mt-8">
    <h2 class="text-2xl font-bold mb-6">Create New Product</h2>

    {{-- Display success message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Product Name --}}
        <div class="mb-4">
            <x-input-label for="name" :value="__('Product Name')" />
            <input id="name" name="name" type="text" class="block mt-1 w-full border-gray-300 rounded" value="{{ old('name') }}" required>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Product Description --}}
        <div class="mb-4">
            <x-input-label for="description" :value="__('Description')" />
            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 rounded">{{ old('description') }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        {{-- Price --}}
        <div class="mb-4">
            <x-input-label for="price" :value="__('Price')" />
            <input id="price" name="price" type="number" step="0.01" class="block mt-1 w-full border-gray-300 rounded" value="{{ old('price') }}" required>
            <x-input-error :messages="$errors->get('price')" class="mt-2" />
        </div>

        {{-- Stock --}}
        <div class="mb-4">
            <x-input-label for="stock" :value="__('Stock')" />
            <input id="stock" name="stock" type="number" class="block mt-1 w-full border-gray-300 rounded" value="{{ old('stock', 0) }}" required>
            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
        </div>

        {{-- Product Image --}}
        <div class="mb-4">
            <x-input-label for="image" :value="__('Product Image')" />
            <input id="image" name="image" type="file" class="block mt-1 w-full" />
            <x-input-error :messages="$errors->get('image')" class="mt-2" />
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Create Product
            </button>
        </div>
    </form>
</div>
@endsection
