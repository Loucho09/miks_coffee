<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="hidden md:flex w-12 h-12 bg-stone-900 dark:bg-amber-600 rounded-2xl items-center justify-center shadow-lg transition-colors duration-500">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <h2 class="font-black text-lg sm:text-3xl text-stone-900 dark:text-white leading-tight uppercase tracking-tighter italic transition-colors duration-500">
                        {{ __('Order Manifest') }}
                    </h2>
                    <p class="text-[7px] sm:text-[10px] text-stone-500 dark:text-stone-400 mt-0.5 font-black uppercase tracking-[0.3em]">Operator: {{ Auth::user()->name }}</p>
                </div>
            </div>
            
            <form method="GET" action="{{ route('home') }}" class="relative w-full md:w-80 group">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Filter inventory..." 
                       class="w-full pl-10 pr-4 py-2 rounded-xl sm:rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 shadow-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 text-stone-900 dark:text-white placeholder-stone-400 transition-all text-[10px] sm:text-xs font-bold uppercase tracking-tight outline-none">
                <svg class="w-3.5 h-3.5 absolute left-3.5 top-2.5 sm:top-3 text-stone-400 group-focus-within:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>
    </x-slot>

    <div class="py-3 sm:py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500" x-data="{ 
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
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            
            {{-- Compact Sticky Category Bar --}}
            <div class="sticky top-16 md:top-20 z-30 -mx-2 px-2 py-2 bg-stone-50/80 dark:bg-stone-950/90 backdrop-blur-xl border-b border-stone-200 dark:border-stone-800 transition-all duration-500">
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar flex-nowrap scroll-smooth md:justify-center">
                    <a href="{{ route('home') }}" 
                       class="whitespace-nowrap px-4 py-1.5 rounded-lg sm:rounded-xl text-[8px] sm:text-[9px] font-black uppercase tracking-widest transition-all duration-300 {{ !request('category') ? 'bg-stone-900 dark:bg-amber-600 text-white shadow-lg' : 'bg-white dark:bg-stone-900 text-stone-400 border border-stone-200 dark:border-stone-800 hover:border-amber-500/50' }}">
                        Full Stack
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('home', ['category' => $category->id]) }}" 
                           class="whitespace-nowrap px-4 py-1.5 rounded-lg sm:rounded-xl text-[8px] sm:text-[9px] font-black uppercase tracking-widest transition-all duration-300 {{ request('category') == $category->id ? 'bg-stone-900 dark:bg-amber-600 text-white shadow-lg' : 'bg-white dark:bg-stone-900 text-stone-400 border border-stone-200 dark:border-stone-800 hover:border-amber-500/50' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Optimized 2-Column Mobile Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-8 mt-6 sm:mt-10">
                @foreach($products as $product)
                    <div class="group relative flex flex-col h-full bg-white dark:bg-stone-900 rounded-[1.5rem] sm:rounded-[2rem] p-2.5 sm:p-4 border border-stone-200 dark:border-stone-800 shadow-sm transition-all duration-500 hover:shadow-xl hover:border-amber-500/30">
                        
                        {{-- Component Visuals --}}
                        <div class="relative aspect-square rounded-xl sm:rounded-[2rem] bg-stone-100 dark:bg-stone-950 overflow-hidden flex items-center justify-center border border-stone-50 dark:border-stone-800 transition-colors duration-500">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 {{ $product->stock_quantity <= 0 ? 'grayscale opacity-40' : '' }}" 
                                     alt="{{ $product->name }}">
                            @else
                                <span class="font-serif italic text-4xl sm:text-8xl opacity-5 uppercase text-stone-900 dark:text-white select-none transition-opacity group-hover:opacity-10">{{ substr($product->name, 0, 1) }}</span>
                            @endif

                            @if($product->stock_quantity <= 0)
                                <div class="absolute inset-0 bg-stone-950/80 backdrop-blur-sm flex items-center justify-center p-2">
                                    <div class="border border-white/20 px-3 py-1 rounded-lg transform -rotate-6">
                                        <p class="text-white font-black text-[7px] sm:text-xs uppercase italic tracking-widest leading-none">Depleted</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Metadata Area --}}
                        <div class="mt-3 sm:mt-4 flex flex-col flex-1 px-1">
                            <div class="flex justify-between items-start gap-2 mb-1.5">
                                <h3 class="text-stone-900 dark:text-white font-black text-[10px] sm:text-lg leading-tight uppercase tracking-tight break-words line-clamp-2 transition-colors duration-500">
                                    {{ $product->name }}
                                </h3>
                            </div>
                            
                            <div class="flex items-center gap-2 mb-3 sm:mb-4">
                                <span class="px-1.5 py-0.5 rounded-md text-[6px] sm:text-[7px] font-black uppercase tracking-widest bg-stone-100 dark:bg-stone-800 text-stone-500 dark:text-stone-400 border border-stone-200 dark:border-stone-700 transition-colors">
                                    {{ $product->category->name ?? 'Brew' }}
                                </span>
                            </div>

                            <div class="mt-auto flex items-center justify-between pt-2 border-t border-stone-100 dark:border-stone-800">
                                <div class="flex flex-col">
                                    <span class="text-amber-600 dark:text-amber-500 font-black text-sm sm:text-2xl leading-none transition-colors duration-500">
                                        {{ number_format($product->sizes->count() > 0 ? $product->sizes->min('price') : $product->price, 0) }}₱
                                    </span>
                                    <span class="text-[6px] sm:text-[7px] text-stone-400 font-bold uppercase tracking-widest mt-0.5">Asset Val.</span>
                                </div>
                                
                                <button @click='openModal(@json($product->load("sizes")))' 
                                        :disabled="{{ $product->stock_quantity <= 0 ? 'true' : 'false' }}"
                                        class="w-8 h-8 sm:w-12 sm:h-12 bg-stone-900 dark:bg-stone-100 text-white dark:text-stone-900 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg active:scale-90 transition-all disabled:opacity-20 disabled:grayscale">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Null Case --}}
            @if($products->isEmpty())
                <div class="flex flex-col items-center justify-center py-24 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-stone-100 dark:bg-stone-900 flex items-center justify-center mb-4 border border-stone-200 dark:border-stone-800">
                        <svg class="w-6 h-6 text-stone-300 dark:text-stone-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-stone-400 dark:text-stone-600 uppercase tracking-tighter">Manifest Null</h3>
                    <p class="text-stone-500 text-[10px] mt-1 italic">Query parameters returned zero results.</p>
                </div>
            @endif
        </div>

        {{-- Selection Modal --}}
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="absolute inset-0 bg-stone-950/95 backdrop-blur-xl" @click="showModal = false"></div>
            <div class="relative bg-white dark:bg-stone-900 w-full max-w-sm rounded-[2.5rem] sm:rounded-[3rem] shadow-2xl border border-stone-200 dark:border-stone-800 p-6 sm:p-8 overflow-hidden transition-all duration-500">
                <form method="POST" action="{{ route('cart.add') }}" class="relative z-10">
                    @csrf
                    <input type="hidden" name="product_id" :value="selectedProduct?.id">
                    <input type="hidden" name="size" :value="selectedSize?.size">
                    <input type="hidden" name="price" :value="selectedSize?.price">
                    
                    <div class="flex justify-between items-start mb-6 sm:mb-8">
                        <div class="min-w-0">
                            <span class="text-amber-600 text-[8px] font-black uppercase tracking-[0.4em] block mb-1 italic leading-none">Init. sequence</span>
                            <h3 class="text-xl sm:text-2xl font-black text-stone-900 dark:text-white uppercase tracking-tighter italic break-words leading-none" x-text="selectedProduct?.name"></h3>
                        </div>
                        <button @click="showModal = false" type="button" class="p-2 text-stone-400 hover:text-stone-900 dark:hover:text-white transition-all active:scale-90">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="space-y-2 mb-8 max-h-60 overflow-y-auto no-scrollbar pr-1">
                        <template x-if="selectedProduct?.sizes && selectedProduct.sizes.length > 0">
                            <template x-for="sizeObj in selectedProduct?.sizes" :key="sizeObj.id">
                                <label class="flex items-center justify-between p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border-2 cursor-pointer transition-all duration-300" 
                                       :class="selectedSize?.id === sizeObj.id ? 'border-amber-600 bg-amber-500/5' : 'border-stone-100 dark:border-stone-800/50 bg-stone-50/50 dark:bg-stone-950/30 hover:border-amber-500/30'">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="size_id" :value="sizeObj.id" required @change="selectedSize = sizeObj" class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 focus:ring-0 bg-transparent border-stone-300 dark:border-stone-700">
                                        <span class="font-black text-[9px] sm:text-[10px] uppercase tracking-widest text-stone-900 dark:text-white" x-text="sizeObj.size"></span>
                                    </div>
                                    <span class="font-black text-sm sm:text-base text-amber-600 dark:text-amber-500 italic" x-text="'₱' + parseFloat(sizeObj.price).toFixed(0)"></span>
                                </label>
                            </template>
                        </template>
                        <template x-if="!selectedProduct?.sizes || selectedProduct.sizes.length === 0">
                            <div class="p-4 sm:p-6 text-center bg-stone-50 dark:bg-stone-950 rounded-2xl sm:rounded-[2rem] border-2 border-stone-100 dark:border-stone-800 border-dashed">
                                <p class="text-[8px] sm:text-[10px] font-black text-stone-400 uppercase italic">Standard configuration active</p>
                            </div>
                        </template>
                    </div>

                    <div class="grid grid-cols-2 gap-2 sm:gap-3">
                        <button type="button" @click="showModal = false" class="py-3.5 sm:py-4 text-stone-400 font-black uppercase text-[8px] sm:text-[9px] tracking-widest hover:text-stone-900 dark:hover:text-white transition-colors">Discard</button>
                        <button type="submit" :disabled="!selectedSize" class="py-3.5 sm:py-4 bg-stone-900 dark:bg-stone-100 text-white dark:text-stone-900 rounded-xl sm:rounded-2xl font-black uppercase text-[8px] sm:text-[9px] tracking-widest active:scale-95 transition-all shadow-xl disabled:opacity-20">Commit order</button>
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