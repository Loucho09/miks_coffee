<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-xl md:text-3xl text-stone-800 dark:text-white leading-tight">
                    Order Menu
                </h2>
                <p class="text-xs md:text-sm text-stone-500 dark:text-stone-400 mt-1 font-medium text-nowrap">Welcome back, {{ Auth::user()->name }}!</p>
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
                this.selectedSize = {
                    id: 'standard',
                    size: 'Standard',
                    price: product.price
                };
            } else {
                this.selectedSize = null; 
            }
            this.showModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center gap-3 mb-8 md:mb-16 overflow-x-auto pb-4 no-scrollbar -mx-4 px-4 md:justify-center md:flex-wrap">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-12">
                @foreach($products as $product)
                    <div class="group relative flex flex-col h-full bg-white dark:bg-stone-900 rounded-[2rem] md:rounded-[2.5rem] border border-stone-100 dark:border-stone-800 transition-all duration-500 hover:border-amber-500/40 hover:shadow-[0_40px_80px_-20px_rgba(245,158,11,0.15)] overflow-hidden">
                        
                        @if($product->stock_quantity <= 0)
                            <div class="absolute inset-0 z-20 bg-stone-950/70 backdrop-blur-[2px] flex items-center justify-center p-6 text-center">
                                <div class="border-2 border-amber-500/50 p-4 rounded-2xl transform -rotate-12">
                                    <p class="text-white font-serif italic text-xl md:text-2xl leading-none">Sold Out</p>
                                    <p class="text-amber-500 text-[8px] uppercase font-black tracking-widest mt-1">Check back later</p>
                                </div>
                            </div>
                        @elseif($product->stock_quantity <= 10)
                            <div class="absolute top-4 left-4 z-10">
                                <span class="px-3 py-1 bg-rose-600 text-white rounded-full text-[8px] md:text-[9px] font-black uppercase tracking-widest animate-pulse shadow-lg">
                                    Only {{ $product->stock_quantity }} left
                                </span>
                            </div>
                        @endif

                        <div class="relative h-56 md:h-72 bg-stone-100 dark:bg-stone-800 overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 {{ $product->stock_quantity <= 0 ? 'grayscale opacity-50' : '' }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-stone-200 dark:bg-stone-800">
                                    <svg class="w-12 h-12 text-stone-400 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                                </div>
                            @endif
                            
                            <div class="absolute bottom-4 left-6 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full border border-white/20">
                                <span class="text-[7px] text-white font-black uppercase tracking-widest">+10 Points per order</span>
                            </div>
                        </div>
                        
                        <div class="p-6 md:p-8 flex-1 flex flex-col">
                            <div class="mb-3">
                                <span class="text-[9px] font-black text-amber-600 uppercase tracking-[0.3em]">{{ $product->category->name ?? 'House Special' }}</span>
                                <h3 class="text-xl font-bold text-stone-900 dark:text-white tracking-tight mt-1 group-hover:text-amber-500 transition-colors">{{ $product->name }}</h3>
                            </div>
                            <p class="text-stone-500 dark:text-stone-400 text-xs font-light leading-relaxed mb-6 flex-1 line-clamp-2">{{ $product->description }}</p>

                            <div class="flex items-center justify-between pt-4 border-t border-stone-50 dark:border-stone-800 mt-auto">
                                <div class="flex flex-col">
                                    @if($product->sizes->count() > 0)
                                        <span class="text-[8px] font-black text-stone-400 uppercase tracking-widest mb-0.5">Starts at</span>
                                        <span class="text-xl font-black text-stone-900 dark:text-white leading-none">
                                            ₱{{ number_format($product->sizes->min('price'), 0) }}
                                        </span>
                                    @else
                                        <span class="text-xl font-black text-stone-900 dark:text-white leading-none">
                                            ₱{{ number_format($product->price, 0) }}
                                        </span>
                                    @endif
                                </div>
                                
                                @if($product->stock_quantity > 0)
                                    <button @click='openModal(@json($product->load("sizes")))' class="p-3 bg-stone-700 hover:bg-stone-500 text-white rounded-xl shadow-lg shadow-amber-600/20 transition-all transform hover:-translate-y-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                @else
                                    <button disabled class="p-3 bg-stone-200 dark:bg-stone-800 text-stone-400 rounded-xl cursor-not-allowed">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($products->isEmpty())
                <div class="flex flex-col items-center justify-center py-32 text-center">
                    <div class="w-24 h-24 rounded-full bg-stone-100 dark:bg-stone-900 flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-stone-400 mb-2">No flavor found</h3>
                    <p class="text-stone-500 mb-8 font-light">Try searching for a different roast or meal.</p>
                    <a href="{{ route('home') }}" class="text-amber-600 font-black uppercase tracking-widest text-[10px] hover:underline">Reset Filters</a>
                </div>
            @endif
        </div> 

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4" x-cloak>
            <div class="absolute inset-0 bg-stone-950/80 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative bg-white dark:bg-stone-900 w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden border border-stone-100 dark:border-stone-800">
                <div class="p-6 md:p-8">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white" x-text="selectedProduct?.name"></h3>
                    <form method="POST" action="{{ route('cart.add') }}" class="mt-6">
                        @csrf
                        <input type="hidden" name="product_id" :value="selectedProduct?.id">
                        <input type="hidden" name="size" :value="selectedSize?.size">
                        <input type="hidden" name="price" :value="selectedSize?.price">

                        <div class="space-y-3 mb-8">
                            <template x-if="selectedProduct?.sizes && selectedProduct.sizes.length > 0">
                                <template x-for="sizeObj in selectedProduct?.sizes" :key="sizeObj.id">
                                    <label class="flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition-all duration-300" 
                                           :class="selectedSize?.id === sizeObj.id ? 'border-amber-500 bg-amber-500/5' : 'border-stone-100 dark:border-stone-800'">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="size_id" :value="sizeObj.id" required @change="selectedSize = sizeObj" class="text-amber-600 focus:ring-amber-500">
                                            <span class="font-bold text-stone-800 dark:text-stone-200" x-text="sizeObj.size"></span>
                                        </div>
                                        <span class="font-black text-amber-600" x-text="'₱' + parseFloat(sizeObj.price).toFixed(0)"></span>
                                    </label>
                                </template>
                            </template>

                            <template x-if="!selectedProduct?.sizes || selectedProduct.sizes.length === 0">
                                <label class="flex items-center justify-between p-4 rounded-xl border-2 border-amber-500 bg-amber-500/5 cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" checked disabled class="text-amber-600">
                                        <span class="font-bold text-stone-800 dark:text-stone-200">Standard Order</span>
                                    </div>
                                    <span class="font-black text-amber-600" x-text="'₱' + parseFloat(selectedProduct?.price).toFixed(0)"></span>
                                </label>
                            </template>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" @click="showModal = false" class="flex-1 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest text-stone-500 bg-stone-100 dark:bg-stone-800 hover:bg-stone-200 transition">Back</button>
                            <button type="submit" :disabled="!selectedSize" class="flex-1 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest text-white bg-amber-600 hover:bg-amber-700 disabled:opacity-50 transition shadow-lg shadow-amber-600/20">Add to Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .group img[src*="favicon.png"], .rounded-full img {
        mix-blend-mode: multiply; 
        background-color: transparent !important;
    }
</style>