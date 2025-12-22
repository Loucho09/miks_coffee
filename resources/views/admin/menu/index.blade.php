<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl md:text-3xl text-stone-900 dark:text-white uppercase tracking-tight">
                    {{ __('Manage Menu') }}
                </h2>
                <p class="text-xs md:text-sm text-stone-500 dark:text-stone-400 mt-1 font-medium italic font-serif">
                    Update your roasted offerings and shop items.
                </p>
            </div>
            <a href="{{ route('admin.menu.create') }}" class="inline-flex items-center gap-2 bg-stone-900 dark:bg-amber-600 hover:bg-amber-700 text-white font-black uppercase text-[10px] tracking-[0.2em] py-3 px-8 rounded-full shadow-xl shadow-amber-600/10 transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Add New Item
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 p-4 bg-green-500/10 border border-green-500/20 rounded-2xl text-green-600 font-bold text-[10px] uppercase tracking-widest flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-stone-900 overflow-hidden shadow-sm rounded-[2.5rem] border border-stone-200 dark:border-stone-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50 dark:bg-stone-800/50 text-stone-400 dark:text-stone-500 text-[10px] font-black uppercase tracking-[0.2em]">
                                <th class="py-6 px-8">Product</th>
                                <th class="py-6 px-6">Category</th>
                                <th class="py-6 px-6">Price Range</th>
                                <th class="py-6 px-6 text-center">Inventory</th>
                                <th class="py-6 px-6 text-center">Status</th>
                                <th class="py-6 px-8 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                            @foreach($products as $product)
                                <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/30 transition-colors group">
                                    <td class="py-6 px-8">
                                        <div class="flex items-center gap-4">
                                            <div class="w-14 h-14 rounded-full bg-stone-100 dark:bg-stone-800 overflow-hidden border border-stone-200 dark:border-stone-700 relative shrink-0">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-stone-100 dark:bg-stone-800">
                                                        <svg class="w-6 h-6 text-stone-300 dark:text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-stone-900 dark:text-white text-base group-hover:text-amber-600 transition-colors">{{ $product->name }}</div>
                                                <div class="text-[9px] text-stone-400 font-black uppercase tracking-widest mt-0.5">SKU-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-6 px-6">
                                        <span class="inline-block bg-stone-100 dark:bg-stone-800 text-stone-500 dark:text-stone-400 py-1.5 px-4 rounded-full text-[9px] font-black uppercase tracking-widest border border-stone-200/50 dark:border-stone-700/50">
                                            {{ $product->category->name ?? 'House Special' }}
                                        </span>
                                    </td>
                                    <td class="py-6 px-6">
                                        @if($product->sizes->count() > 0)
                                            <div class="text-stone-900 dark:text-white font-black text-sm">
                                                ₱{{ number_format($product->sizes->min('price'), 0) }} - ₱{{ number_format($product->sizes->max('price'), 0) }}
                                            </div>
                                            <div class="text-[9px] text-amber-600 font-black uppercase tracking-tighter mt-0.5">Variable Pricing</div>
                                        @else
                                            <div class="text-stone-900 dark:text-white font-black text-sm">₱{{ number_format($product->price, 0) }}</div>
                                            <div class="text-[9px] text-stone-400 font-black uppercase tracking-tighter mt-0.5">Fixed Rate</div>
                                        @endif
                                    </td>
                                    <td class="py-6 px-6 text-center">
                                        <div class="inline-flex flex-col items-center">
                                            <span class="text-base font-black {{ $product->stock_quantity < 10 ? 'text-rose-600' : 'text-stone-900 dark:text-white' }}">
                                                {{ $product->stock_quantity }}
                                            </span>
                                            <span class="text-[8px] uppercase font-black tracking-widest text-stone-400">In Stock</span>
                                        </div>
                                    </td>
                                    <td class="py-6 px-6 text-center">
                                        <span class="inline-flex h-2 w-2 rounded-full {{ $product->is_active ? 'bg-green-500' : 'bg-stone-300' }} shadow-sm"></span>
                                    </td>
                                    <td class="py-6 px-8 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.menu.edit', $product->id) }}" class="p-2.5 bg-stone-100 dark:bg-stone-800 text-stone-500 dark:text-stone-400 hover:bg-amber-600 hover:text-white rounded-xl transition-all shadow-sm" title="Edit Item">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            
                                            <form action="{{ route('admin.menu.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Archive this item from the menu?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2.5 bg-stone-100 dark:bg-stone-800 text-stone-400 hover:bg-rose-600 hover:text-white rounded-xl transition-all shadow-sm" title="Delete Item">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="p-8 border-t border-stone-100 dark:border-stone-800">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>