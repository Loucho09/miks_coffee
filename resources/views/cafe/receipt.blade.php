<x-app-layout>
    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500">
        <div class="max-w-xl mx-auto px-4">
            <div class="bg-white dark:bg-stone-900 rounded-[2.5rem] p-8 sm:p-12 shadow-2xl border border-stone-200 dark:border-stone-800 transition-all duration-500">
                
                <div class="text-center mb-10">
                    <div class="w-20 h-20 bg-amber-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl animate-bounce">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h2 class="text-3xl font-black text-stone-900 dark:text-white uppercase tracking-tighter italic leading-none">Order Committed</h2>
                    <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mt-3">Manifest dispatched to: {{ Auth::user()->email }}</p>
                </div>

                <div class="space-y-6 border-t border-stone-100 dark:border-stone-800 pt-8 mb-10">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-start group">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-black text-stone-900 dark:text-white uppercase tracking-tight">{{ $item->product_name }}</h4>
                                    @php
                                        $isGoldenHour = $item->product?->is_happy_hour_active && (float)$item->price <= (float)$item->product?->happy_hour_price;
                                    @endphp
                                    @if($isGoldenHour)
                                        <span class="px-2 py-0.5 bg-amber-600 text-white text-[7px] font-black uppercase rounded-md tracking-widest animate-pulse">Golden Hour</span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-stone-500 font-bold uppercase tracking-widest">{{ $item->size }}</p>
                                @if($item->customizations)
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        @foreach($item->customizations as $label => $price)
                                            <span class="text-[8px] bg-stone-100 dark:bg-stone-800 text-stone-400 dark:text-stone-500 px-2 py-1 rounded-lg font-black uppercase tracking-tight">+ {{ $label }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-black text-stone-900 dark:text-white tracking-tighter">₱{{ number_format($item->price * $item->quantity, 0) }}</span>
                                <p class="text-[8px] text-stone-400 font-bold uppercase">Qty: {{ $item->quantity }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-stone-50 dark:bg-stone-950 p-6 rounded-[2rem] border border-stone-100 dark:border-stone-800">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[9px] font-black text-stone-400 uppercase tracking-widest">Transaction ID</span>
                        <span class="text-[9px] font-black text-stone-900 dark:text-white uppercase tracking-tighter">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between items-end border-t border-stone-200 dark:border-stone-800 pt-4 mt-2">
                        <span class="text-[10px] font-black text-amber-600 uppercase tracking-[0.2em]">Net Assets</span>
                        <span class="text-4xl font-black text-stone-900 dark:text-white tracking-tighter italic leading-none">₱{{ number_format($order->total_price, 0) }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-10">
                    <a href="{{ route('orders.index') }}" class="py-4 border border-stone-200 dark:border-stone-800 text-stone-500 dark:text-stone-400 text-center rounded-2xl font-black uppercase text-[9px] tracking-widest hover:bg-stone-50 dark:hover:bg-stone-800 transition-all">Order History</a>
                    <a href="{{ route('home') }}" class="py-4 bg-stone-900 dark:bg-white text-white dark:text-stone-900 text-center rounded-2xl font-black uppercase text-[9px] tracking-widest shadow-xl active:scale-95 transition-all">Back to Menu</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>