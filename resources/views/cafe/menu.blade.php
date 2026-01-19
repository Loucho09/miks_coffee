<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl md:text-3xl text-stone-900 dark:text-white uppercase tracking-tight">
                    {{ __('Our Menu Collection') }}
                </h2>
                <p class="text-xs md:text-sm text-stone-500 dark:text-stone-400 mt-1 font-medium italic font-serif">
                    Monitor and explore our handcrafted roasted offerings.
                </p>
            </div>
            
            <form method="GET" action="{{ route('menu.index') }}" class="relative w-full md:w-80">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Find a flavor..." 
                       class="w-full pl-11 pr-4 py-2.5 rounded-2xl border-none bg-stone-100 dark:bg-stone-800 shadow-inner focus:ring-2 focus:ring-amber-500 text-stone-900 dark:text-white placeholder-stone-500 transition-all text-sm">
                <svg class="w-4 h-4 absolute left-4 top-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-6 py-4 rounded-[2rem] relative mb-10 shadow-sm flex items-center gap-3" role="alert">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="block sm:inline font-bold">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Category Chips Section --}}
            <div class="flex items-center gap-3 mb-10 overflow-x-auto pb-4 no-scrollbar -mx-4 px-4 md:justify-center md:flex-wrap">
                <a href="{{ route('menu.index') }}" 
                   class="whitespace-nowrap px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all duration-300 {{ !request('category') ? 'bg-amber-600 text-white shadow-xl shadow-amber-600/20' : 'bg-white dark:bg-stone-900 text-stone-500 border border-stone-200 dark:border-stone-800 hover:border-amber-500' }}">
                    All Items
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('menu.index', ['category' => $category->slug]) }}" 
                       class="whitespace-nowrap px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all duration-300 {{ request('category') == $category->slug ? 'bg-amber-600 text-white shadow-xl shadow-amber-600/20' : 'bg-white dark:bg-stone-900 text-stone-500 border border-stone-200 dark:border-stone-800 hover:border-amber-500' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            @if($products->isEmpty())
                <div class="bg-white dark:bg-stone-900 rounded-[3rem] shadow-sm border border-stone-200 dark:border-stone-800 p-20 text-center">
                    <div class="w-20 h-20 bg-stone-100 dark:bg-stone-800 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl text-stone-300">☕</span>
                    </div>
                    <h3 class="text-2xl font-bold text-stone-400 dark:text-stone-500 mb-2">No flavor found</h3>
                    <p class="text-stone-500 mb-8 font-light italic">Try searching for a different roast or meal.</p>
                    <a href="{{ route('menu.index') }}" class="text-amber-600 font-black uppercase tracking-widest text-[10px] hover:underline">Reset Filters</a>
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

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 md:gap-10">
                    @foreach($products as $product)
                        {{-- 🟢 ENHANCED OPTION A/B CARD UI --}}
                        <div class="group relative flex flex-col h-full bg-white dark:bg-stone-900 rounded-[2.5rem] border border-stone-100 dark:border-stone-800 transition-all duration-500 hover:shadow-2xl overflow-hidden">
                            
                            {{-- Floating Price Overlay --}}
                            <div class="absolute top-5 right-5 z-20">
                                <span class="bg-white/95 dark:bg-stone-800/95 backdrop-blur-md text-stone-900 dark:text-amber-500 px-4 py-1.5 rounded-2xl font-black shadow-sm border border-stone-100 dark:border-stone-700">
                                    ₱{{ number_format($product->price, 0) }}
                                </span>
                            </div>

                            {{-- Popular / Promo Badges --}}
                            @if(in_array($product->id, $popularIds))
                                <div class="absolute top-5 left-5 z-20">
                                    <span class="bg-amber-500 text-stone-900 text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest flex items-center gap-1.5 shadow-lg animate-pulse">
                                        Popular
                                    </span>
                                </div>
                            @endif

                            {{-- Image Area --}}
                            <div class="relative h-64 md:h-72 bg-stone-100 dark:bg-stone-800 overflow-hidden rounded-b-[2.5rem]">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 {{ $product->stock_quantity <= 0 ? 'grayscale opacity-40' : '' }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-stone-200 dark:bg-stone-800">
                                        <span class="font-serif italic text-8xl text-stone-800 dark:text-stone-200 opacity-[0.05] uppercase select-none">{{ substr($product->name, 0, 1) }}</span>
                                    </div>
                                @endif

                                <div class="absolute bottom-6 left-6 bg-white/10 dark:bg-black/20 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/10">
                                    <span class="text-[7px] text-white font-black uppercase tracking-widest">+10 Loyalty Points</span>
                                </div>
                            </div>

                            {{-- Content Area --}}
                            <div class="p-8 flex-1 flex flex-col">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1 pr-4">
                                        <h3 class="text-xl font-bold text-stone-900 dark:text-white tracking-tight group-hover:text-amber-500 transition-colors">
                                            {{ $product->name }}
                                        </h3>
                                        <span class="text-[9px] font-black text-amber-600 uppercase tracking-[0.3em] block mt-0.5">
                                            {{ $product->category->name ?? 'House Special' }}
                                        </span>
                                    </div>
                                    <span class="text-[9px] font-black text-stone-400 uppercase tracking-widest pt-2">Order Now ↗</span>
                                </div>

                                <p class="text-stone-500 dark:text-stone-400 text-xs font-light leading-relaxed mb-6 flex-1 line-clamp-2 italic">
                                    {{ $product->description ?? 'Expertly crafted for a premium experience.' }}
                                </p>

                                {{-- Tag Pills --}}
                                <div class="flex flex-wrap gap-2 mb-8 mt-auto">
                                    <span class="px-3 py-1 bg-stone-100 dark:bg-stone-800 text-stone-500 dark:text-stone-400 rounded-full text-[9px] font-bold uppercase tracking-tighter transition-colors group-hover:bg-amber-500/10 group-hover:text-amber-600">Fresh</span>
                                    <span class="px-3 py-1 bg-stone-100 dark:bg-stone-800 text-stone-500 dark:text-stone-400 rounded-full text-[9px] font-bold uppercase tracking-tighter transition-colors group-hover:bg-amber-500/10 group-hover:text-amber-600">Premium</span>
                                </div>

                                {{-- Action Button --}}
                                <div class="mt-auto">
                                    @if($product->stock_quantity > 0)
                                        <a href="{{ route('cart.add', $product->id) }}" 
                                           class="w-full py-4 bg-stone-900 dark:bg-stone-800 hover:bg-amber-600 hover:text-white text-white dark:text-amber-500 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all text-center block shadow-lg hover:shadow-amber-600/20 active:scale-95">
                                            Add to Order
                                        </a>
                                    @else
                                        <button disabled 
                                                class="w-full py-4 bg-stone-200 dark:bg-stone-800 text-stone-400 rounded-2xl font-black text-[10px] uppercase tracking-widest cursor-not-allowed">
                                            Sold Out
                                        </button>
                                    @endif
                                </div>
                                <div class="text-[8px] font-black text-stone-400 uppercase tracking-widest mt-4 text-right">
                                    Only {{ $product->stock_quantity }} items left
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>


<!-- css -->
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .group img[src*="favicon.png"], .rounded-full img { mix-blend-mode: multiply; background-color: transparent !important; }
</style>