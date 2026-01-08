<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="hidden md:flex w-12 h-12 bg-amber-600 rounded-2xl items-center justify-center shadow-lg shadow-amber-600/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl md:text-3xl text-stone-800 dark:text-white leading-tight uppercase tracking-tighter">
                        Order Menu
                    </h2>
                    <p class="text-[10px] md:text-xs text-stone-500 dark:text-stone-400 mt-1 font-bold uppercase tracking-widest">Welcome, {{ Auth::user()->name }}!</p>
                </div>
            </div>
            
            <form method="GET" action="{{ route('home') }}" class="relative w-full md:w-80">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search our flavors..." 
                       class="w-full pl-11 pr-4 py-2.5 rounded-2xl border-none bg-stone-100 dark:bg-stone-800 shadow-inner focus:ring-2 focus:ring-amber-500 text-stone-900 dark:text-white placeholder-stone-500 transition-all text-sm font-medium">
                <svg class="w-4 h-4 absolute left-4 top-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-stone-50 dark:bg-[#080808] min-h-screen" x-data="{ 
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
            
            <div class="sticky top-16 z-30 -mx-4 px-4 py-4 bg-white/80 dark:bg-[#080808]/90 backdrop-blur-xl border-b border-stone-200 dark:border-white/5 transition-colors">
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar flex-nowrap scroll-smooth md:justify-center">
                    <a href="{{ route('home') }}" 
                       class="whitespace-nowrap px-6 py-2.5 rounded-full text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-300 {{ !request('category') ? 'bg-amber-600 text-white shadow-xl shadow-amber-600/20 scale-105' : 'bg-stone-100 dark:bg-stone-900 text-stone-500 border border-transparent hover:border-amber-500' }}">
                        All Items
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('home', ['category' => $category->id]) }}" 
                           class="whitespace-nowrap px-6 py-2.5 rounded-full text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-300 {{ request('category') == $category->id ? 'bg-amber-600 text-white shadow-xl shadow-amber-600/20 scale-105' : 'bg-stone-100 dark:bg-stone-900 text-stone-500 border border-transparent hover:border-amber-500' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 mt-10">
                @foreach($products as $product)
                    <div class="group relative flex flex-col h-full bg-[#E7DEBE] dark:bg-black rounded-[3rem] p-4 border border-white/5 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                        
                        <div class="relative aspect-square rounded-[2.5rem] bg-[#E1E6D8] dark:bg-[#1E2216] overflow-hidden flex items-center justify-center">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 {{ $product->stock_quantity <= 0 ? 'grayscale opacity-40' : '' }}" 
                                     alt="{{ $product->name }}">
                            @else
                                <span class="font-serif italic text-8xl opacity-10 uppercase text-stone-400">{{ substr($product->name, 0, 1) }}</span>
                            @endif

                            @if($product->stock_quantity <= 0)
                                <div class="absolute inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-6">
                                    <div class="border-2 border-white/20 px-8 py-4 rounded-3xl transform -rotate-12">
                                        <p class="text-white font-black text-xl uppercase italic">Sold Out</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 flex justify-between items-end gap-3 px-2 pb-2">
                            <div class="flex-1 flex flex-col min-w-0">
                                <h3 class="text-white font-bold text-lg leading-tight uppercase tracking-tighter break-words">
                                    {{ $product->name }}
                                </h3>
                                
                                <div class="flex flex-wrap gap-1 mt-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest bg-white/5 text-stone-700 border border-white/10">
                                        {{ $product->category->name ?? 'Brew' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col items-end pl-4 min-w-[80px] border-l border-white/10 shrink-0">
                                <span class="text-amber-500 font-black text-2xl md:text-3xl leading-none">
                                    {{ number_format($product->sizes->count() > 0 ? $product->sizes->min('price') : $product->price, 0) }}₱
                                </span>
                                <button @click='openModal(@json($product->load("sizes")))' 
                                        :disabled="{{ $product->stock_quantity <= 0 ? 'true' : 'false' }}"
                                        class="mt-2 flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-stone-700 hover:text-amber-500 transition-colors group/btn disabled:opacity-30 whitespace-nowrap">
                                    <span>Order Now</span>
                                    <svg class="w-3 h-3 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </div>
                        </div>

                        <button @click='openModal(@json($product->load("sizes")))' 
                                :disabled="{{ $product->stock_quantity <= 0 ? 'true' : 'false' }}"
                                class="mt-4 w-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white rounded-2xl py-3.5 font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-amber-600/20 active:scale-95 transition-all disabled:opacity-30 disabled:cursor-not-allowed">
                            Add to Cart
                        </button>
                    </div>
                @endforeach
            </div>

            @if($products->isEmpty())
                <div class="flex flex-col items-center justify-center py-40 text-center">
                    <div class="w-24 h-24 rounded-3xl bg-stone-100 dark:bg-stone-900 flex items-center justify-center mb-8 border border-stone-200 dark:border-stone-800">
                        <svg class="w-10 h-10 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-3xl font-black text-stone-400 uppercase tracking-tighter mb-2">No flavor found</h3>
                    <p class="text-stone-500 dark:text-stone-600 mb-8 font-medium italic">Adjust your filters or search keywords.</p>
                    <a href="{{ route('home') }}" 
                       class="inline-block text-amber-600 font-black uppercase tracking-[0.2em] text-[10px] px-10 py-4 border-2 border-amber-600/20 rounded-full bg-transparent hover:bg-amber-600 hover:text-white hover:border-amber-600 transition-all duration-300 shadow-lg">
                        Reset Filters
                    </a>
                </div>
            @endif
        </div>

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4" x-cloak>
            <div class="absolute inset-0 bg-stone-950/90 backdrop-blur-md" @click="showModal = false"></div>
            <div class="relative bg-white dark:bg-stone-900 w-full max-w-md rounded-[3rem] shadow-2xl p-8 overflow-hidden">
                <form method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" :value="selectedProduct?.id">
                    <input type="hidden" name="size" :value="selectedSize?.size">
                    <input type="hidden" name="price" :value="selectedSize?.price">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-stone-900 dark:text-white uppercase tracking-tight break-words" x-text="selectedProduct?.name"></h3>
                            <p class="text-[10px] font-black text-amber-600 uppercase mt-1">Select your size</p>
                        </div>
                        <button @click="showModal = false" type="button" class="text-stone-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="space-y-3 mb-10">
                        <template x-if="selectedProduct?.sizes && selectedProduct.sizes.length > 0">
                            <template x-for="sizeObj in selectedProduct?.sizes" :key="sizeObj.id">
                                <label class="flex items-center justify-between p-5 rounded-2xl border-2 cursor-pointer transition-all" :class="selectedSize?.id === sizeObj.id ? 'border-amber-500 bg-amber-500/5' : 'border-stone-100 dark:border-stone-800'">
                                    <div class="flex items-center gap-4">
                                        <input type="radio" name="size_id" :value="sizeObj.id" required @change="selectedSize = sizeObj" class="text-amber-600 focus:ring-amber-500 bg-transparent">
                                        <span class="font-bold text-stone-800 dark:text-stone-200" x-text="sizeObj.size"></span>
                                    </div>
                                    <span class="font-black text-amber-600" x-text="'₱' + parseFloat(sizeObj.price).toFixed(0)"></span>
                                </label>
                            </template>
                        </template>
                    </div>
                    <div class="flex gap-4">
                        <button type="button" @click="showModal = false" class="flex-1 py-4 text-stone-500 font-black uppercase text-[10px] bg-stone-100 dark:bg-stone-800 rounded-2xl">Cancel</button>
                        <button type="submit" :disabled="!selectedSize" class="flex-1 py-4 bg-amber-600 text-white rounded-2xl font-black uppercase text-[10px] active:scale-95 transition-all">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    [x-cloak] { display: none !important; }
</style>