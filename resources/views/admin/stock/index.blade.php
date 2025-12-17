<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-stone-800 dark:text-white leading-tight">
            {{ __('Inventory Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-stone-900 overflow-hidden shadow-sm rounded-2xl p-6 border border-stone-100 dark:border-stone-800 flex items-center">
                    <div class="p-4 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 text-2xl mr-4">⚠️</div>
                    <div>
                        <p class="text-sm font-bold text-stone-500 dark:text-stone-400 uppercase tracking-wide">Low Stock</p>
                        <p class="text-3xl font-extrabold text-amber-600">{{ $lowStockCount }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-stone-900 overflow-hidden shadow-sm rounded-2xl p-6 border border-stone-100 dark:border-stone-800 flex items-center">
                    <div class="p-4 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 text-2xl mr-4">🚫</div>
                    <div>
                        <p class="text-sm font-bold text-stone-500 dark:text-stone-400 uppercase tracking-wide">Out of Stock</p>
                        <p class="text-3xl font-extrabold text-red-600">{{ $outOfStockCount }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-stone-900 overflow-hidden shadow-sm rounded-2xl p-6 border border-stone-100 dark:border-stone-800 flex items-center">
                    <div class="p-4 rounded-full bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400 text-2xl mr-4">📦</div>
                    <div>
                        <p class="text-sm font-bold text-stone-500 dark:text-stone-400 uppercase tracking-wide">Total Items</p>
                        <p class="text-3xl font-extrabold text-stone-900 dark:text-white">{{ $totalItems }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-stone-900 overflow-hidden shadow-sm rounded-2xl border border-stone-100 dark:border-stone-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50 dark:bg-stone-800 text-stone-500 dark:text-stone-400 text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 font-semibold">Product Name</th>
                                <th class="py-4 px-6 font-semibold">Category</th>
                                <th class="py-4 px-6 font-semibold text-center">Status</th>
                                <th class="py-4 px-6 font-semibold text-center">Stock Level</th>
                                <th class="py-4 px-6 font-semibold text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-stone-800 text-stone-700 dark:text-stone-300">
                            @foreach($stocks as $product)
                                <tr class="hover:bg-stone-50 dark:hover:bg-stone-800/50 transition">
                                    <td class="py-4 px-6 font-bold text-stone-900 dark:text-white">
                                        {{ $product->name }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400 py-1 px-3 rounded-full text-xs font-bold">
                                            {{ $product->category->name }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if($product->stock_quantity == 0)
                                            <span class="bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 py-1 px-3 rounded-full text-xs font-bold uppercase">
                                                Sold Out
                                            </span>
                                        @elseif($product->stock_quantity < 10)
                                            <span class="bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 py-1 px-3 rounded-full text-xs font-bold uppercase">
                                                Low Stock
                                            </span>
                                        @else
                                            <span class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 py-1 px-3 rounded-full text-xs font-bold uppercase">
                                                In Stock
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="text-lg font-bold {{ $product->stock_quantity < 10 ? 'text-red-600 dark:text-red-400' : 'text-stone-900 dark:text-white' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ route('admin.menu.edit', $product->id) }}" class="text-sm font-bold text-amber-600 hover:text-amber-700 hover:underline">
                                            Restock
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($stocks->isEmpty())
                    <div class="p-12 text-center text-stone-500 dark:text-stone-400">
                        <p class="text-lg">Inventory is empty.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>