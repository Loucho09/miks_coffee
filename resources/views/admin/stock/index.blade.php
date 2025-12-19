<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-amber-600 font-black uppercase tracking-[0.4em] text-[10px] mb-1 block">Warehouse</span>
                <h2 class="font-black text-3xl text-stone-900 dark:text-white leading-tight tracking-tight uppercase">
                    {{ __('Inventory Management') }}
                </h2>
            </div>
            <div class="hidden md:flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-stone-400">Live Stock Tracking</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                
                <div class="group relative p-8 rounded-[2.5rem] bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:border-amber-500/50 hover:shadow-[0_20px_40px_-15px_rgba(245,158,11,0.15)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-1">Attention Required</p>
                            <p class="text-3xl font-black text-amber-600 tracking-tighter">{{ $lowStockCount }} Items</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20">
                            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group relative p-8 rounded-[2.5rem] bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:border-rose-500/50 hover:shadow-[0_20px_40px_-15px_rgba(225,29,72,0.15)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-1">Depleted Stock</p>
                            <p class="text-3xl font-black text-rose-600 tracking-tighter">{{ $outOfStockCount }} Items</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-rose-500/10 flex items-center justify-center border border-rose-500/20">
                            <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group relative p-8 rounded-[2.5rem] bg-stone-900 text-white shadow-2xl transition-all duration-500 hover:shadow-amber-900/20 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-600/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-1">Global Catalog</p>
                            <p class="text-3xl font-black text-white tracking-tighter">{{ $totalItems }} SKU</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-stone-800 flex items-center justify-center border border-stone-700">
                            <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-stone-900 rounded-[3rem] border border-stone-200 dark:border-stone-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-stone-50/50 dark:bg-stone-800/50 text-stone-400 text-[10px] font-black uppercase tracking-[0.4em]">
                                <th class="px-10 py-6">Product Details</th>
                                <th class="px-10 py-6">Category</th>
                                <th class="px-10 py-6 text-center">Health Status</th>
                                <th class="px-10 py-6 text-center">Current Quantity</th>
                                <th class="px-10 py-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-stone-800 text-sm">
                            @foreach($stocks as $product)
                                <tr class="hover:bg-stone-50/80 dark:hover:bg-stone-800/40 transition-colors duration-300">
                                    <td class="px-10 py-8">
                                        <p class="font-black text-stone-900 dark:text-white text-lg tracking-tight">{{ $product->name }}</p>
                                        <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest mt-1">SKU: MK-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </td>
                                    <td class="px-10 py-8">
                                        <span class="px-4 py-1.5 rounded-full bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400 text-[10px] font-black uppercase tracking-widest border border-stone-200 dark:border-stone-700">
                                            {{ $product->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-10 py-8">
                                        <div class="flex justify-center">
                                            @if($product->stock_quantity == 0)
                                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-500/10 text-rose-500 border border-rose-500/20 text-[10px] font-black uppercase tracking-widest">
                                                    <span class="w-1 h-1 rounded-full bg-rose-500 animate-ping"></span>
                                                    Sold Out
                                                </div>
                                            @elseif($product->stock_quantity < 10)
                                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 text-amber-500 border border-amber-500/20 text-[10px] font-black uppercase tracking-widest">
                                                    <span class="w-1 h-1 rounded-full bg-amber-500 animate-pulse"></span>
                                                    Critically Low
                                                </div>
                                            @else
                                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 text-[10px] font-black uppercase tracking-widest">
                                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                                    Healthy
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-10 py-8 text-center">
                                        <span class="text-xl font-black {{ $product->stock_quantity < 10 ? 'text-rose-600' : 'text-stone-900 dark:text-white' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                        <span class="text-[10px] font-black text-stone-400 uppercase ml-1 tracking-widest">Units</span>
                                    </td>
                                    <td class="px-10 py-8 text-right">
                                        <a href="{{ route('admin.menu.edit', $product->id) }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-amber-600 hover:text-amber-700 transition-all group">
                                            Restock Inventory
                                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($stocks->isEmpty())
                    <div class="p-32 text-center flex flex-col items-center">
                        <div class="w-20 h-20 rounded-[2rem] bg-stone-100 dark:bg-stone-800 flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                        <h4 class="text-stone-400 font-light italic text-lg tracking-tight">The warehouse is currently empty.</h4>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>