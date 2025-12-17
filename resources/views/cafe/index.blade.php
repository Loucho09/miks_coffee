<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-stone-800 dark:text-white leading-tight">
                    Order Menu
                </h2>
                <p class="text-stone-500 dark:text-stone-400 mt-1">Welcome back, {{ Auth::user()->name }}!</p>
            </div>
            
            <form method="GET" action="{{ route('home') }}" class="relative w-full md:w-80">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search items..." 
                       class="w-full pl-11 pr-4 py-2.5 rounded-xl border-none bg-stone-100 dark:bg-[#1C1C1E] shadow-inner focus:ring-2 focus:ring-amber-500 text-stone-900 dark:text-white placeholder-stone-500 transition-all">
                <svg class="w-5 h-5 absolute left-4 top-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ 
        showModal: false, 
        selectedProduct: null, 
        openModal(product) {
            console.log(product); // Debug: Check console to see if sizes exist
            this.selectedProduct = product;
            this.showModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex overflow-x-auto pb-4 gap-3 mb-8 no-scrollbar">
                <a href="{{ route('home') }}" 
                   class="whitespace-nowrap px-6 py-2.5 rounded-full font-bold text-sm transition-all duration-200 
                   {{ !request('category') 
                      ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' 
                      : 'bg-stone-200 dark:bg-[#2C2C2E] text-stone-600 dark:text-stone-300 hover:bg-stone-300 dark:hover:bg-stone-700' }}">
                    All Items
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('home', ['category' => $category->id]) }}" 
                       class="whitespace-nowrap px-6 py-2.5 rounded-full font-bold text-sm transition-all duration-200 
                       {{ request('category') == $category->id 
                          ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' 
                          : 'bg-stone-200 dark:bg-[#2C2C2E] text-stone-600 dark:text-stone-300 hover:bg-stone-300 dark:hover:bg-stone-700' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="group bg-white dark:bg-[#1C1C1E] rounded-3xl p-4 shadow-sm hover:shadow-xl transition-all duration-300 border border-stone-100 dark:border-stone-800/50 flex flex-col">
                        
                        <div class="relative w-full aspect-[4/3] bg-stone-100 dark:bg-[#2C2C2E] rounded-2xl overflow-hidden mb-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center opacity-50">
                                    <svg class="w-12 h-12 text-stone-400 dark:text-stone-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M2,21H20V19H2M20,8H18V5H20M20,3H4V13A4,4 0 0,0 8,17H14A4,4 0 0,0 18,13V10H20A2,2 0 0,0 22,8V5C22,3.89 21.1,3 20,3Z" />
                                    </svg>
                                </div>
                            @endif

                            @if($product->sizes->count() > 0)
                                <button type="button" 
                                        @click='openModal(@json($product))'
                                        class="absolute bottom-2 right-3 text-amber-500 hover:text-amber-400 text-4xl leading-none transition-transform hover:scale-110 active:scale-95">
                                    +
                                </button>
                            @else
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" 
                                            class="absolute bottom-2 right-3 text-amber-500 hover:text-amber-400 text-4xl leading-none transition-transform hover:scale-110 active:scale-95">
                                        +
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="flex-1 flex flex-col">
                            <h3 class="font-bold text-stone-900 dark:text-white text-lg leading-tight mb-1">
                                {{ $product->name }}
                            </h3>
                            <p class="text-sm text-stone-500 dark:text-stone-400 line-clamp-2 mb-4 flex-1">
                                {{ $product->description }}
                            </p>
                            
                            <div class="font-extrabold text-xl text-stone-900 dark:text-white">
                                ₱{{ number_format($product->price, 2) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($products->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="text-6xl mb-4 opacity-50">🔍</div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white mb-2">No items found</h3>
                    <p class="text-stone-500 dark:text-stone-400 mb-6">Try searching for something else.</p>
                    <a href="{{ route('home') }}" class="px-6 py-2 bg-stone-200 dark:bg-stone-800 rounded-full font-bold text-stone-700 dark:text-stone-300 hover:bg-stone-300 dark:hover:bg-stone-700 transition">
                        Clear Filters
                    </a>
                </div>
            @endif
        </div>

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4" x-cloak>
            
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal = false"></div>

            <div class="relative bg-white dark:bg-[#1C1C1E] w-full max-w-md rounded-3xl shadow-2xl overflow-hidden border border-stone-800">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white mb-2" x-text="selectedProduct?.name"></h3>
                    <p class="text-stone-500 dark:text-stone-400 text-sm mb-6" x-text="selectedProduct?.description"></p>

                    <form method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" x-model="selectedProduct?.id">

                        <div class="space-y-3 mb-8">
                            <p class="text-xs font-bold text-stone-400 uppercase tracking-wide">Select Size</p>
                            
                            <template x-if="selectedProduct?.sizes && selectedProduct.sizes.length > 0">
                                <template x-for="size in selectedProduct.sizes" :key="size.id">
                                    <label class="flex items-center justify-between p-4 rounded-xl border border-stone-200 dark:border-stone-700 cursor-pointer hover:border-amber-500 hover:bg-stone-50 dark:hover:bg-stone-800 transition">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="size_id" :value="size.id" required class="text-amber-600 focus:ring-amber-500 border-gray-300">
                                            <span class="font-bold text-stone-800 dark:text-white" x-text="size.size"></span>
                                        </div>
                                        <span class="font-bold text-amber-600" x-text="'₱' + parseFloat(size.price).toFixed(2)"></span>
                                    </label>
                                </template>
                            </template>

                            <template x-if="!selectedProduct?.sizes || selectedProduct.sizes.length === 0">
                                <label class="flex items-center justify-between p-4 rounded-xl border border-amber-500 bg-amber-50 dark:bg-amber-900/10 cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="size_id" value="" checked class="text-amber-600 focus:ring-amber-500 border-gray-300">
                                        <span class="font-bold text-stone-800 dark:text-white">Regular</span>
                                    </div>
                                    <span class="font-bold text-amber-600" x-text="'₱' + parseFloat(selectedProduct?.price).toFixed(2)"></span>
                                </label>
                            </template>
                        </div>

                        <div class="flex gap-4">
                            <button type="button" @click="showModal = false" class="flex-1 py-3 rounded-xl font-bold text-stone-600 dark:text-stone-300 bg-stone-100 dark:bg-stone-800 hover:bg-stone-200 dark:hover:bg-stone-700 transition">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 py-3 rounded-xl font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-lg shadow-amber-600/20 transition">
                                Add to Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>