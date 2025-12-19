<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add New Item') }}
            </h2>
            <a href="{{ route('admin.menu.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                &larr; Back to Menu
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ hasSizes: false }">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl p-8 transition-colors duration-300">
                
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg relative">
                        <strong class="font-bold">Whoops!</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data">
                    @csrf 

                    <div class="mb-6">
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Product Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 dark:file:bg-gray-700 dark:file:text-gray-300 cursor-pointer" required>
                    </div>

                    <div class="mb-6">
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Product Name</label>
                        <input type="text" name="name" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm" required>
                    </div>

                    <div class="mb-6">
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Category</label>
                        <select name="category_id" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm" required>
                            <option value="" disabled selected>Select a Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="has_sizes" x-model="hasSizes" value="1" class="w-5 h-5 text-amber-600 rounded focus:ring-amber-500 bg-white dark:bg-gray-700 border-gray-300">
                            <span class="font-bold text-sm text-gray-700 dark:text-gray-300">This drink has multiple sizes (12oz & 16oz)</span>
                        </label>

                        <div x-show="hasSizes" x-transition class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-amber-600 uppercase mb-1">12oz Price (₱)</label>
                                <input type="number" name="size_prices[12oz]" value="39" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-amber-600 uppercase mb-1">16oz Price (₱)</label>
                                <input type="number" name="size_prices[16oz]" value="49" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div x-show="!hasSizes">
                            <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Standard Price (₱)</label>
                            <input type="number" name="price" step="0.01" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm">
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Initial Stock</label>
                            <input type="number" name="stock_quantity" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm" required>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('admin.menu.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                            Save Item
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>