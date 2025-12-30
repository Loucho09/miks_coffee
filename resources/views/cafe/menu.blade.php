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

            @if(request('category'))
                @php $currentCategory = $categories->where('slug', request('category'))->first(); @endphp
                @if($currentCategory && $currentCategory->image)
                    <div class="relative h-48 sm:h-64 w-full rounded-[2.5rem] overflow-hidden mb-10 shadow-lg border border-gray-100 group">
                        <img src="{{ asset('storage/' . $currentCategory->image) }}" alt="{{ $currentCategory->name }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex items-end p-8 sm:p-12">
                            <div>
                                <span class="text-amber-400 text-[10px] font-black uppercase tracking-[0.4em] mb-2 block">Menu Section</span>
                                <h1 class="text-white text-3xl sm:text-5xl font-black uppercase tracking-tighter italic leading-none">{{ $currentCategory->name }}</h1>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if($products->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 text-center">
                    <p class="text-gray-500 text-xl">No items found matching your search. ☕</p>
                    <a href="{{ route('menu.index') }}" class="text-brand-orange hover:underline mt-2 inline-block">View Full Menu</a>
                </div>
            @else
                @php
                    $popularIds = \App\Models\OrderItem::select('product_id', \DB::raw('SUM(quantity) as total_sold'))
                        ->groupBy('product_id')
                        ->orderByDesc('total_sold')
                        ->take(10)
                        ->pluck('product_id')
                        ->toArray();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300 relative">
                            
                            @if(in_array($product->id, $popularIds))
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="bg-amber-500 text-stone-900 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest flex items-center gap-1.5 shadow-lg animate-pulse">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        Most Popular
                                    </span>
                                </div>
                            @endif

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

                                {{-- 🟢 NEW FEATURE: Bulk Discount Badge --}}
                                <div class="mt-1">
                                    <span class="inline-block bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-200">
                                        PROMO: 10% OFF ON 6+ UNITS
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