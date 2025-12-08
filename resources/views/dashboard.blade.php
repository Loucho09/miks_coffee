<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}

                    <div class="p-6 text-gray-900">
    <h3 class="font-bold text-xl mb-4">Current Menu</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($products as $product)
            <div class="border p-4 rounded-lg bg-white shadow flex justify-between items-center">
                <div>
                    <h4 class="font-bold text-lg">{{ $product->name }}</h4>
                    <span class="text-xs font-semibold bg-gray-200 px-2 py-1 rounded">{{ $product->category }}</span>
                    <p class="text-sm text-gray-600">{{ $product->description }}</p>
                </div>
                <div class="text-xl font-bold text-green-600">
                    ₱{{ number_format($product->price, 0) }}
                </div>
            </div>
        @endforeach
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
