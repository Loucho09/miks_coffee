<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-stone-900 dark:text-white leading-tight uppercase tracking-tight italic">
                {{ __('Edit Menu Item') }}
            </h2>
            <a href="{{ route('admin.menu.index') }}" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-stone-500 hover:text-amber-600 transition-colors duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Manifest
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500" 
         x-data="{ 
            hasSizes: {{ $product->sizes->count() > 0 ? 'true' : 'false' }},
            imageUrl: '{{ $product->image ? asset('storage/' . $product->image) : '' }}',
            fileChosen(event) {
                const file = event.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (e) => { this.imageUrl = e.target.result; };
            }
         }">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-stone-900 overflow-hidden shadow-2xl sm:rounded-[2rem] border border-stone-200 dark:border-stone-800 p-8 transition-all duration-500">
                
                @if ($errors->any())
                    <div class="mb-8 p-5 bg-red-50 dark:bg-red-900/10 border-l-4 border-red-600 rounded-r-2xl overflow-hidden">
                        <div class="flex items-center gap-4 mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-red-600">Validation Error</h3>
                        </div>
                        <ul class="list-disc list-inside text-xs text-stone-500 dark:text-stone-400 font-medium uppercase tracking-widest space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.menu.update', $product->id) }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf 
                    @method('PUT') 

                    {{-- Image Protocol --}}
                    <div class="p-6 bg-stone-50 dark:bg-stone-800/30 rounded-3xl border border-stone-100 dark:border-stone-800 transition-all">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-stone-400 mb-4">Asset Visualization</label>
                        <div class="flex flex-col sm:flex-row items-center gap-8">
                            <div class="relative w-40 h-40 bg-white dark:bg-stone-900 rounded-[2rem] overflow-hidden border-2 border-dashed border-stone-200 dark:border-stone-700 flex items-center justify-center group shadow-inner">
                                <template x-if="imageUrl">
                                    <img :src="imageUrl" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                </template>
                                <template x-if="!imageUrl">
                                    <div class="text-center p-4">
                                        <svg class="w-8 h-8 text-stone-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-[8px] font-black uppercase text-stone-400">Null Image</p>
                                    </div>
                                </template>
                            </div>
                            <div class="flex-1 w-full space-y-4">
                                <p class="text-[10px] text-stone-500 font-medium leading-relaxed italic">Upload a high-resolution PNG or JPG for the order manifest. Maximum file size: 50MB.</p>
                                <input type="file" name="image" @change="fileChosen" accept="image/*" class="w-full text-[10px] text-stone-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- Core Configuration --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-stone-400 mb-2">Item Name</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="e.g., Caramel Macchiato" class="w-full px-5 py-4 rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 text-stone-900 dark:text-white font-bold focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all outline-none" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-stone-400 mb-2">Category Segment</label>
                            <select name="category_id" class="w-full px-5 py-4 rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 text-stone-900 dark:text-white font-bold focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all outline-none" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Variant Logic --}}
                    <div class="p-6 bg-stone-50 dark:bg-stone-800/30 rounded-3xl border border-dashed border-stone-300 dark:border-stone-700 transition-all">
                        <label class="flex items-center gap-4 cursor-pointer select-none">
                            <input type="checkbox" name="has_sizes" x-model="hasSizes" value="1" class="w-6 h-6 text-amber-600 rounded-lg focus:ring-amber-500 bg-white dark:bg-stone-900 border-stone-300 dark:border-stone-700">
                            <span class="text-[10px] font-black uppercase tracking-widest text-stone-700 dark:text-stone-300">Enable Multi-Size Protocol (12oz & 16oz)</span>
                        </label>

                        <div x-show="hasSizes" x-collapse x-cloak class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="p-4 bg-white dark:bg-stone-900 rounded-2xl border border-stone-100 dark:border-stone-800 shadow-sm">
                                <label class="block text-[8px] font-black text-amber-600 uppercase tracking-widest mb-2 italic">12oz Yield Price</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3 text-stone-400 font-bold">₱</span>
                                    <input type="number" name="size_prices[12oz]" step="0.01" value="{{ $product->sizes->where('size', '12oz')->first()?->price ?? 0 }}" class="w-full pl-8 pr-4 py-3 rounded-xl border border-stone-100 dark:border-stone-800 bg-stone-50 dark:bg-stone-950 text-stone-900 dark:text-white font-black">
                                </div>
                            </div>
                            <div class="p-4 bg-white dark:bg-stone-900 rounded-2xl border border-stone-100 dark:border-stone-800 shadow-sm">
                                <label class="block text-[8px] font-black text-amber-600 uppercase tracking-widest mb-2 italic">16oz Yield Price</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3 text-stone-400 font-bold">₱</span>
                                    <input type="number" name="size_prices[16oz]" step="0.01" value="{{ $product->sizes->where('size', '16oz')->first()?->price ?? 0 }}" class="w-full pl-8 pr-4 py-3 rounded-xl border border-stone-100 dark:border-stone-800 bg-stone-50 dark:bg-stone-950 text-stone-900 dark:text-white font-black">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Happy Hour Protocol --}}
                    <div class="p-6 bg-amber-500/5 dark:bg-amber-900/10 rounded-3xl border border-amber-200 dark:border-amber-800/50">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                            </div>
                            <span class="text-[10px] font-black text-amber-700 dark:text-amber-400 uppercase tracking-[0.2em]">Happy Hour Protocol</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[8px] font-black text-stone-400 dark:text-stone-500 mb-2 uppercase tracking-widest">Rate Adjustment (%)</label>
                                <input type="number" name="happy_hour_discount" value="{{ old('happy_hour_discount', $product->happy_hour_discount) }}" placeholder="e.g. 20" class="w-full px-4 py-3 rounded-xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 text-stone-900 dark:text-white focus:ring-amber-500 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-[8px] font-black text-stone-400 dark:text-stone-500 mb-2 uppercase tracking-widest">Start Time</label>
                                <input type="time" name="happy_hour_start" value="{{ old('happy_hour_start', $product->happy_hour_start) }}" class="w-full px-4 py-3 rounded-xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 text-stone-900 dark:text-white focus:ring-amber-500 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-[8px] font-black text-stone-400 dark:text-stone-500 mb-2 uppercase tracking-widest">End Time</label>
                                <input type="time" name="happy_hour_end" value="{{ old('happy_hour_end', $product->happy_hour_end) }}" class="w-full px-4 py-3 rounded-xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 text-stone-900 dark:text-white focus:ring-amber-500 transition-all outline-none">
                            </div>
                        </div>
                    </div>

                    {{-- Operations & Pricing --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div x-show="!hasSizes">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-stone-400 mb-2">Standard Valuation (₱)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-4 text-stone-400 font-bold">₱</span>
                                <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" class="w-full pl-8 pr-4 py-4 rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 text-stone-900 dark:text-white font-black focus:ring-2 focus:ring-amber-500/50 outline-none transition-all">
                            </div>
                        </div>
                        <div :class="hasSizes ? 'md:col-span-2' : ''">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-stone-400 mb-2">Inventory Stock Count</label>
                            <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="w-full px-5 py-4 rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 text-stone-900 dark:text-white font-black focus:ring-2 focus:ring-amber-500/50 outline-none transition-all" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-stone-400 mb-2">Item Description</label>
                        <textarea name="description" rows="3" placeholder="Define the assets profile..." class="w-full px-5 py-4 rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 text-stone-50 dark:text-white font-medium focus:ring-2 focus:ring-amber-500/50 outline-none transition-all resize-none">{{ old('description', $product->description) }}</textarea>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-6 pt-6 border-t border-stone-100 dark:border-stone-800">
                        <a href="{{ route('admin.menu.index') }}" class="text-[10px] font-black uppercase tracking-widest text-stone-400 hover:text-stone-900 dark:hover:text-white transition-colors">Abort Changes</a>
                        <button type="submit" class="bg-stone-900 dark:bg-white text-white dark:text-stone-900 font-black px-10 py-5 rounded-2xl shadow-xl uppercase tracking-[0.2em] text-xs transition-all hover:bg-amber-600 hover:text-white active:scale-95">
                            Update Asset Manifest
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>