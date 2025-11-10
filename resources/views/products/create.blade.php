<x-app-layout> 
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST"> <!-- added admin prefix -->
    @csrf

    <!-- Name field -->
    <x-input-label for="name" :value="__('Name')" />
    <x-text-input id="name" name="name" class="block mt-1 w-full" type="text" :value="old('name')" required autofocus />

    <!-- Description field -->
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" class="block mt-1 w-full">{{ old('description') }}</textarea>

    <!-- Price field -->
    <x-input-label for="price" :value="__('Price')" />
    <x-text-input id="price" name="price" class="block mt-1 w-full" type="number" :value="old('price')" required step="0.01" />

    <!-- Stock field -->
    <x-input-label for="stock" :value="__('Stock')" />
    <x-text-input id="stock" name="stock" class="block mt-1 w-full" type="number" :value="old('stock')" required />

    <!-- Buttons -->
    <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900 mr-4"> <!-- admin prefix -->
        {{ __('Cancel') }}
    </a>
    <x-primary-button>{{ __('Save Product') }}</x-primary-button>
</form>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
