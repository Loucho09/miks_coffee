<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New Item
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('product.store') }}">
                    @csrf 

                    <div class="mb-4">
                        <label class="block font-bold mb-1">Product Name</label>
                        <input type="text" name="name" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-1">Category</label>
                        <select name="category" class="w-full border rounded p-2">
                            <option>Coffee</option>
                            <option>Pasta</option>
                            <option>Matcha</option>
                            <option>Cookies</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-1">Price</label>
                        <input type="number" name="price" step="0.01" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-1">Description</label>
                        <textarea name="description" class="w-full border rounded p-2"></textarea>
                    </div>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Save Item
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>