<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Our Menu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form method="GET" action="{{ route('menu.index') }}">
                    
                    <div class="flex gap-2 mb-4">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search for coffee, meals..." 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-brand-orange focus:ring-brand-orange">
                        <button type="submit" class="bg-coffee-800 text-white px-6 py-2 rounded-md hover:bg-coffee-600">
                            Search
                        </button>
                    </div>

                    <div class="flex gap-2 overflow-x-auto pb-2 no-scrollbar">
                        <a href="{{ route('menu.index') }}" 
                           class="px-4 py-1 rounded-full text-sm font-semibold whitespace-nowrap border transition
                           {{ !request('category') ? 'bg-brand-orange text-white border-brand-orange' : 'bg-gray-100 text-gray-600 border-gray-200 hover:bg-gray-200' }}">
                           All
                        </a>
                        
                        @foreach($categories as $category)
                            <a href="{{ route('menu.index', ['category' => $category->slug]) }}" 
                               class="px-4 py-1 rounded-full text-sm font-semibold whitespace-nowrap border transition
                               {{ request('category') == $category->slug ? 'bg-brand-orange text-white border-brand-orange' : 'bg-gray-100 text-gray-600 border-gray-200 hover:bg-gray-200' }}">
                               {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </form>
            </div>
            @if($products->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 text-center">
                    <p class="text-gray-500 text-xl">No items found matching your search. ☕</p>
                    <a href="{{ route('menu.index') }}" class="text-brand-orange hover:underline mt-2 inline-block">View Full Menu</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                            
                            <div class="h-48 w-full overflow-hidden bg-gray-200 relative group">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex items-center justify-center h-full bg-amber-100 text-amber-500">
                                        <span class="text-4xl">☕</span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6">
                                <div class="flex justify-between items-baseline">
                                    <span class="text-sm text-amber-600 font-bold tracking-wide uppercase">
                                        {{ $product->category->name ?? 'Special' }}
                                    </span>
                                    <span class="text-lg font-bold text-gray-900">
                                        ₱{{ number_format($product->price, 2) }}
                                    </span>
                                </div>

                                <h3 class="mt-2 text-xl font-semibold text-gray-900">
                                    {{ $product->name }}
                                </h3>

                                <p class="mt-2 text-gray-600 text-sm h-12 overflow-hidden">
                                    {{ $product->description ?? 'Deliciously brewed perfection.' }}
                                </p>

                                <div class="mt-4 flex items-center justify-between">
                                    @if($product->stock_quantity > 0)
                                        <a href="{{ route('cart.add', $product->id) }}" class="block text-center w-full bg-coffee-800 hover:bg-coffee-600 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                                            Add to Order
                                        </a>
                                    @else
                                        <button disabled class="block text-center w-full bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed">
                                            Sold Out
                                        </button>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 mt-2 text-right">
                                    {{ $product->stock_quantity }} items left
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>