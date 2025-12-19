<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-stone-800 dark:text-white">
                {{ __('Manage Menu') }}
            </h2>
            <a href="{{ route('admin.menu.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-6 rounded-full shadow-md transition transform hover:-translate-y-0.5">
                + Add Item
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-stone-900 overflow-hidden shadow-sm rounded-2xl border border-stone-100 dark:border-stone-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50 dark:bg-stone-800 text-stone-500 dark:text-stone-400 text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 font-semibold">Image</th>
                                <th class="py-4 px-6 font-semibold">Product Name</th>
                                <th class="py-4 px-6 font-semibold">Category</th>
                                <th class="py-4 px-6 font-semibold">Price</th>
                                <th class="py-4 px-6 font-semibold text-center">Stock</th>
                                <th class="py-4 px-6 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                            @foreach($products as $product)
                                <tr class="hover:bg-stone-50 dark:hover:bg-stone-800/50 transition">
                                    <td class="py-4 px-6">
                                        <div class="w-12 h-12 rounded-lg bg-stone-100 dark:bg-stone-800 overflow-hidden border border-stone-200 dark:border-stone-700 relative">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-lg text-stone-400">☕</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 font-bold text-stone-900 dark:text-white">
                                        {{ $product->name }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400 py-1 px-3 rounded-full text-xs font-bold">
                                            {{ $product->category->name ?? 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-stone-700 dark:text-stone-300">
                                        {{-- 🟢 FIXED: Check if product has sizes --}}
                                        @if($product->sizes->count() > 0)
                                            <span class="text-amber-600 font-bold">
                                                ₱{{ number_format($product->sizes->min('price'), 2) }} - ₱{{ number_format($product->sizes->max('price'), 2) }}
                                            </span>
                                            <div class="text-[10px] text-gray-500 uppercase font-black tracking-tighter">Sizes Active</div>
                                        @else
                                            ₱{{ number_format($product->price, 2) }}
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="{{ $product->stock_quantity < 10 ? 'text-red-500 font-bold' : 'text-stone-600 dark:text-stone-400' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('admin.menu.edit', $product->id) }}" class="text-stone-500 hover:text-amber-600 font-medium transition">Edit</a>
                                            
                                            <form action="{{ route('admin.menu.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-stone-400 hover:text-red-600 font-medium transition">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>