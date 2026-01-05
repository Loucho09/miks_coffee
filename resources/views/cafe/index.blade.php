<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-xl md:text-3xl text-stone-800 dark:text-white leading-tight">
                    Order Menu
                </h2>
                <p class="text-xs md:text-sm text-stone-500 dark:text-stone-400 mt-1 font-medium italic">Welcome back, {{ Auth::user()->name }}!</p>
            </div>
            
            <form method="GET" action="{{ route('home') }}" class="relative w-full md:w-80">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search our flavors..." 
                       class="w-full pl-11 pr-4 py-2.5 rounded-2xl border-none bg-stone-100 dark:bg-stone-800 shadow-inner focus:ring-2 focus:ring-amber-500 text-stone-900 dark:text-white placeholder-stone-500 transition-all text-sm">
                <svg class="w-4 h-4 absolute left-4 top-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>
    </x-slot>

    <div class="py-6 md:py-12" x-data="{ 
        showModal: false, 
        selectedProduct: null, 
        selectedSize: null,
        openModal(product) {
            if(product.stock_quantity <= 0) return;
            this.selectedProduct = product;
            if (!product.sizes || product.sizes.length === 0) {
                this.selectedSize = { id: 'standard', size: 'Standard', price: product.price };
            } else { this.selectedSize = null; }
            this.showModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center gap-3 mb-10 overflow-x-auto pb-4 no-scrollbar -mx-4 px-4 md:justify-center md:flex-wrap sticky top-20 z-30 bg-stone-100/80 dark:bg-stone-950/80 backdrop-blur-md transition-colors">
                <a href="{{ route('home') }}" 
                   class="whitespace-nowrap px-6 py-2.5 rounded-full text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-300 {{ !request('category') ? 'bg-amber-600 text-white shadow-xl shadow-amber-600/20' : 'bg-white dark:bg-stone-900 text-stone-500 border border-stone-200 dark:border-stone-800 hover:border-amber-500' }}">
                    All Items
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('home', ['category' => $category->id]) }}" 
                       class="whitespace-nowrap px-6 py-2.5 rounded-full text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-300 {{ request('category') == $category->id ? 'bg-amber-600 text-white shadow-xl shadow-amber-600/20' : 'bg-white dark:bg-stone-900 text-stone-500 border border-stone-200 dark:border-stone-800 hover:border-amber-500' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 md:gap-10">
                @foreach($products as $product)
                    @php $isAltStyle = $loop->iteration % 2 === 0; @endphp

                    <div class="group relative flex flex-col h-full rounded-[2.5rem] border transition-all duration-500 overflow-hidden shadow-sm hover:shadow-2xl
                        {{ $isAltStyle ? 'bg-stone-900 border-stone-800' : 'bg-white border-stone-100 dark:bg-stone-900 dark:border-stone-800' }}">
                        
                        <div class="absolute top-5 right-5 z-20">
                            <div class="bg-white/95 dark:bg-stone-800/95 backdrop-blur-md px-4 py-1.5 rounded-2xl shadow-sm border border-stone-100 dark:border-stone-700 transition-transform group-hover:scale-110">
                                <span class="text-sm font-black text-stone-900 dark:text-amber-500">
                                    ₱{{ number_format($product->sizes->count() > 0 ? $product->sizes->min('price') : $product->price, 0) }}
                                </span>
                            </div>
                        </div>

                        @if($product->stock_quantity <= 0)
                            <div class="absolute inset-0 z-30 bg-stone-950/75 backdrop-blur-[1.5px] flex items-center justify-center p-6 text-center">
                                <div class="border-2 border-amber-500/50 p-4 rounded-3xl transform -rotate-12">
                                    <p class="text-white font-serif italic text-2xl leading-none">Sold Out</p>
                                    <p class="text-amber-500 text-[8px] uppercase font-black tracking-widest mt-1">Visit us tomorrow</p>
                                </div>
                            </div>
                        @endif

                        <div class="relative h-64 md:h-72 overflow-hidden {{ $isAltStyle ? 'bg-stone-800' : 'bg-stone-100 dark:bg-stone-800' }}">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 {{ $product->stock_quantity <= 0 ? 'grayscale opacity-40' : '' }}" alt="{{ $product->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-stone-200 dark:bg-stone-800">
                                    <span class="font-serif italic text-8xl text-stone-800 dark:text-stone-200 opacity-[0.05] uppercase select-none">{{ substr($product->name, 0, 1) }}</span>
                                </div>
                            @endif

                            <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
                                <svg class="relative block w-full h-8" viewBox="0 0 1200 120" preserveAspectRatio="none">
                                    <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C58.42,102.3,121.2,104,180.11,96.27,240.37,88.41,281.39,69.09,321.39,56.44Z" 
                                          class="{{ $isAltStyle ? 'fill-stone-900' : 'fill-white dark:fill-stone-900' }}"></path>
                                </svg>
                            </div>
                            
                            <div class="absolute bottom-4 left-6 bg-black/10 backdrop-blur-sm px-3 py-1 rounded-full border border-white/5">
                                <span class="text-[7px] text-white font-black uppercase tracking-widest">+10 Loyalty Points</span>
                            </div>
                        </div>
                        
                        @if(!$isAltStyle)
                            <div class="p-8 flex-1 flex flex-col">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1 pr-4">
                                        <h3 class="text-xl font-bold text-stone-900 dark:text-white tracking-tight group-hover:text-amber-500 transition-colors leading-tight">{{ $product->name }}</h3>
                                        <span class="text-[9px] font-black text-amber-600 uppercase tracking-[0.3em] block mt-1.5 opacity-80">{{ $product->category->name ?? 'House Special' }}</span>
                                    </div>
                                    <button @click='openModal(@json($product->load("sizes")))' class="text-[9px] font-black uppercase tracking-widest text-stone-400 hover:text-amber-600 transition-colors pt-1.5">Order Now ↗</button>
                                </div>

                                <p class="text-stone-500 dark:text-stone-400 text-xs font-light leading-relaxed mb-6 line-clamp-2 italic">{{ $product->description }}</p>

                                <div class="flex flex-wrap gap-2 mb-8 mt-auto">
                                    <span class="px-3 py-1 bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400 rounded-full text-[9px] font-bold uppercase tracking-tighter">Premium</span>
                                    <span class="px-3 py-1 bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400 rounded-full text-[9px] font-bold uppercase tracking-tighter">Ice Cubes</span>
                                    <span class="px-3 py-1 bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400 rounded-full text-[9px] font-bold uppercase tracking-tighter">Honey</span>
                                </div>

                                <button @click='openModal(@json($product->load("sizes")))' 
                                        :disabled="{{ $product->stock_quantity <= 0 ? 'true' : 'false' }}"
                                        class="w-full py-4 bg-stone-900 dark:bg-stone-800 hover:bg-amber-600 hover:text-white text-white dark:text-amber-500 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-lg active:scale-95">
                                    {{ $product->stock_quantity > 0 ? 'Select Options' : 'Currently Unavailable' }}
                                </button>
                            </div>
                        @else
                            <div class="flex flex-col flex-1">
                                <div class="p-8 flex-1">
                                    <h3 class="text-xl font-bold text-white tracking-tight group-hover:text-amber-500 transition-colors">{{ $product->name }}</h3>
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <span class="px-2 py-0.5 bg-white/5 text-stone-400 text-[8px] font-black uppercase rounded-md border border-white/10">Kiwi</span>
                                        <span class="px-2 py-0.5 bg-white/5 text-stone-400 text-[8px] font-black uppercase rounded-md border border-white/10">Ice Cream</span>
                                    </div>
                                    <p class="text-stone-400 text-xs font-light leading-relaxed mt-6 line-clamp-2">{{ $product->description }}</p>
                                </div>

                                <div class="flex border-t border-white/5 h-20">
                                    <div class="w-1/2 flex flex-col justify-center px-8 border-r border-white/5">
                                        <span class="text-[8px] font-black text-stone-500 uppercase tracking-widest mb-0.5">Price</span>
                                        <span class="text-2xl font-black text-white leading-none">₱{{ number_format($product->price, 0) }}</span>
                                    </div>
                                    <button @click='openModal(@json($product->load("sizes")))' 
                                            class="w-1/2 bg-amber-600 hover:bg-amber-500 transition-colors flex items-center justify-center gap-2">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-white">Order Now</span>
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($products->isEmpty())
                <div class="flex flex-col items-center justify-center py-40 text-center">
                    <div class="w-24 h-24 rounded-full bg-stone-100 dark:bg-stone-900 flex items-center justify-center mb-8">
                        <svg class="w-10 h-10 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-3xl font-bold text-stone-400 mb-2">No flavor found</h3>
                    <p class="text-stone-500 mb-8 font-light">Try searching for a different roast or meal category.</p>
                    <a href="{{ route('home') }}" class="text-amber-600 font-black uppercase tracking-widest text-[10px] hover:underline px-8 py-3 border border-amber-600/20 rounded-full transition-all">Reset Filters</a>
                </div>
            @endif
        </div> 

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4" x-cloak>
            <div class="absolute inset-0 bg-stone-950/90 backdrop-blur-md" @click="showModal = false"></div>
            <div class="relative bg-white dark:bg-stone-900 w-full max-w-md rounded-[2.5rem] shadow-2xl border border-stone-100 dark:border-stone-800 p-8 md:p-12 overflow-hidden">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-2xl font-bold text-stone-900 dark:text-white leading-tight" x-text="selectedProduct?.name"></h3>
                        <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mt-1">Configure your selection</p>
                    </div>
                    <button @click="showModal = false" class="text-stone-400 hover:text-stone-600 dark:hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" :value="selectedProduct?.id">
                    <input type="hidden" name="size" :value="selectedSize?.size">
                    <input type="hidden" name="price" :value="selectedSize?.price">
                    <div class="space-y-3 mb-10">
                        <template x-if="selectedProduct?.sizes && selectedProduct.sizes.length > 0">
                            <template x-for="sizeObj in selectedProduct?.sizes" :key="sizeObj.id">
                                <label class="flex items-center justify-between p-5 rounded-2xl border-2 cursor-pointer transition-all duration-300" :class="selectedSize?.id === sizeObj.id ? 'border-amber-500 bg-amber-500/5' : 'border-stone-100 dark:border-stone-800'">
                                    <div class="flex items-center gap-4">
                                        <input type="radio" name="size_id" :value="sizeObj.id" required @change="selectedSize = sizeObj" class="text-amber-600 focus:ring-amber-500 bg-transparent border-stone-300 dark:border-stone-600">
                                        <span class="font-bold text-stone-800 dark:text-stone-200" x-text="sizeObj.size"></span>
                                    </div>
                                    <span class="font-black text-amber-600" x-text="'₱' + parseFloat(sizeObj.price).toFixed(0)"></span>
                                </label>
                            </template>
                        </template>
                        <template x-if="!selectedProduct?.sizes || selectedProduct.sizes.length === 0">
                            <label class="flex items-center justify-between p-5 rounded-2xl border-2 border-amber-500 bg-amber-500/5 cursor-default">
                                <div class="flex items-center gap-4">
                                    <input type="radio" checked disabled class="text-amber-600 border-amber-500 bg-transparent">
                                    <span class="font-bold text-stone-800 dark:text-stone-200">Standard Portion</span>
                                </div>
                                <span class="font-black text-amber-600" x-text="'₱' + parseFloat(selectedProduct?.price).toFixed(0)"></span>
                            </label>
                        </template>
                    </div>
                    <div class="flex gap-4">
                        <button type="button" @click="showModal = false" class="flex-1 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest text-stone-500 bg-stone-100 dark:bg-stone-800 hover:bg-stone-200 dark:hover:bg-stone-700 transition">Cancel</button>
                        <button type="submit" :disabled="!selectedSize" class="flex-1 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest text-white bg-amber-600 hover:bg-amber-700 disabled:opacity-50 transition shadow-lg shadow-amber-600/20 active:scale-95">Add to Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .group img[src*="favicon.png"] { mix-blend-mode: multiply; background-color: transparent !important; }
</style>