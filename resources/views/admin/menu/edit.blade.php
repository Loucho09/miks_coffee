<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Menu Item') }}
            </h2>
            <a href="{{ route('admin.menu.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                &larr; Back to Menu
            </a>
        </div>
    </x-slot>

    <div class="py-12">
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

                <form method="POST" action="{{ route('admin.menu.update', $product->id) }}" enctype="multipart/form-data">
                    @csrf 
                    @method('PUT') 

                    <div class="mb-6">
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Product Image</label>
                        
                        @if($product->image)
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Current Image:</p>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Current" class="w-32 h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm">
                            </div>
                        @endif

                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 dark:file:bg-gray-700 dark:file:text-gray-300 cursor-pointer">
                        <p class="text-xs text-gray-500 mt-2">Upload a new file to replace the current image.</p>
                    </div>

                    <div class="mb-6">
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Product Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm" required>
                    </div>

                    <div class="mb-6">
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Category</label>
                        <select name="category_id" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Price (₱)</label>
                            <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm" required>
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Stock Quantity</label>
                            <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm" required>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition shadow-sm">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('admin.menu.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                            Update Item
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>