<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl md:text-3xl text-stone-900 dark:text-white uppercase tracking-tight">{{ __('Your Cart') }}</h2>
            <div class="flex items-center gap-2 px-4 py-1.5 bg-stone-100 dark:bg-stone-800 rounded-full border border-stone-200 dark:border-stone-700">
                <span class="text-[10px] font-black uppercase text-stone-500 tracking-widest">Items</span>
                <span class="text-xs font-black text-amber-600">{{ session('cart') ? count(session('cart')) : 0 }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('error'))<div class="mb-8 p-5 bg-white dark:bg-stone-900 border-l-4 border-red-600 shadow-sm rounded-r-2xl animate-pulse"><h3 class="text-[10px] font-black uppercase text-red-600">Error</h3><p class="text-[11px] text-stone-500 font-medium uppercase mt-1">{{ session('error') }}</p></div>@endif

            @if(session('cart') && count(session('cart')) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    <div class="lg:col-span-2 space-y-6">
                        @foreach(session('cart') as $id => $details)
                            <div class="group bg-white dark:bg-stone-900 p-6 rounded-[2rem] border border-stone-100 dark:border-stone-800 flex flex-col sm:flex-row items-center justify-between transition-all duration-300 hover:shadow-xl hover:border-amber-500/20">
                                <div class="flex items-center gap-6 w-full">
                                    <div class="relative w-24 h-24 bg-stone-100 dark:bg-stone-800 rounded-3xl overflow-hidden shrink-0 border border-stone-200 dark:border-stone-700 shadow-inner">
                                        @if(isset($details['image']))<img src="{{ asset('storage/' . $details['image']) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">@endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-black text-lg text-stone-900 dark:text-white uppercase tracking-tight italic">{{ $details['name'] }}</h3>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-xs font-bold text-amber-600">PHP {{ number_format($details['price'], 2) }}</span>
                                            <span class="w-1 h-1 bg-stone-300 dark:bg-stone-700 rounded-full"></span>
                                            <span class="text-[10px] font-black text-stone-400 uppercase italic">{{ $details['size'] }} • Qty: {{ $details['quantity'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between w-full sm:w-auto mt-6 sm:mt-0 gap-6">
                                    <div class="text-right">
                                        <p class="text-[8px] font-black text-stone-400 uppercase tracking-widest mb-0.5 italic">Line Total</p>
                                        @php
                                            $itemTotal = (float) $details['price'];
                                            if(isset($details['customizations'])) { foreach($details['customizations'] as $c) { $itemTotal += (float) $c; } }
                                        @endphp
                                        <p class="font-black text-xl text-stone-900 dark:text-white italic">₱{{ number_format($itemTotal * $details['quantity'], 2) }}</p>
                                    </div>
                                    <form action="{{ route('cart.remove') }}" method="POST">@csrf @method('DELETE')<input type="hidden" name="id" value="{{ $id }}"><button class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-900/10 text-red-500 hover:bg-red-600 hover:text-white active:scale-95 transition-all flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-stone-900 p-8 rounded-[3rem] shadow-2xl border border-stone-200 dark:border-stone-800 sticky top-24 transition-colors">
    <h3 class="font-black text-2xl text-stone-900 dark:text-white uppercase italic tracking-tighter mb-8 pb-4 border-b border-stone-200 dark:border-stone-800 transition-colors">
        Order Summary
    </h3>
    
    @php
        $subtotal = 0;
        foreach(session('cart') as $details) {
            $line = (float) $details['price'];
            if(isset($details['customizations'])) foreach($details['customizations'] as $c) $line += (float) $c;
            $subtotal += $line * $details['quantity'];
        }
        $userPoints      = Auth::user()->loyalty_points ?? 0;
        $claimed         = session('claimed_reward');
        $discountPercent = $claimed ? (float)$claimed['percent'] : 0;
        $discountValue   = $subtotal * ($discountPercent / 100);
    @endphp

    <div class="space-y-4 mb-8">
        <div class="flex justify-between items-center text-[10px] font-black text-stone-500 dark:text-stone-400 uppercase tracking-[0.3em] italic transition-colors">
            <span>Subtotal</span>
            <span class="text-stone-900 dark:text-white font-black">PHP {{ number_format($subtotal, 2) }}</span>
        </div>

        @if($claimed)
            <div class="flex justify-between items-center p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl animate-pulse transition-colors">
                <div class="flex flex-col text-left">
                    <span class="text-[8px] font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-widest leading-none mb-1">Active Power</span>
                    <span class="text-xs font-black text-stone-900 dark:text-white uppercase italic tracking-tight">{{ $claimed['name'] }}</span>
                </div>
                <span class="text-emerald-600 dark:text-emerald-500 font-black text-sm italic">-₱{{ number_format($discountValue, 2) }}</span>
            </div>
        @endif

        <div id="discount-row" class="hidden justify-between items-center p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl transition-colors">
            <span class="text-xs font-black text-stone-900 dark:text-white uppercase italic">Instant 1% Power</span>
            <span class="text-emerald-600 dark:text-emerald-500 font-black text-sm italic">-₱{{ number_format($subtotal * 0.01, 2) }}</span>
        </div>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf

        @if($claimed && isset($claimed['key']))
            <input type="hidden" name="claimed_reward_key" value="{{ $claimed['key'] }}">
        @endif

        <div class="bg-stone-50 dark:bg-stone-950 p-6 rounded-[2rem] mb-8 border border-stone-200 dark:border-stone-800 shadow-inner transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="font-black text-stone-500 uppercase text-[9px] tracking-[0.4em] italic">Assets</span>
                <span class="px-2 py-1 bg-amber-100 dark:bg-amber-600/10 border border-amber-200 dark:border-amber-500/20 text-amber-700 dark:text-amber-500 rounded-lg text-[10px] font-black italic transition-colors">
                    Balance: {{ number_format($userPoints) }} PTS
                </span>
            </div>

            @if(!$claimed)
                @if($userPoints >= 100)
                    <label class="flex items-center gap-4 cursor-pointer select-none group">
                        <input type="checkbox" id="redeem-checkbox" name="redeem_points" value="1" class="peer sr-only">
                        
                        {{-- 🟢 FIX: Checkbox custom styles optimized for light/dark --}}
                        <div class="w-7 h-7 bg-white dark:bg-stone-900 border-2 border-stone-300 dark:border-stone-800 rounded-lg peer-checked:bg-amber-500 peer-checked:border-amber-500 dark:peer-checked:bg-amber-600 dark:peer-checked:border-amber-600 transition-all flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        
                        <div class="text-[10px] text-stone-600 dark:text-stone-400 font-bold uppercase italic leading-tight text-left transition-colors">
                            Authorize <span class="text-stone-900 dark:text-white">100 PTS</span> for <span class="text-emerald-600 dark:text-emerald-500">1% OFF</span>
                        </div>
                    </label>
                @else
                    <div class="text-[9px] text-stone-500 dark:text-stone-600 font-black uppercase tracking-widest italic opacity-80 dark:opacity-60">100 PTS required for 1% power.</div>
                @endif
            @else
                <div class="flex items-center justify-center gap-3 py-1 text-emerald-600 dark:text-emerald-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-[9px] font-black uppercase tracking-widest italic">Power Authorized</span>
                </div>
            @endif
        </div>

        <div class="mb-10 text-center">
            <span class="text-[10px] font-black text-stone-500 dark:text-stone-400 uppercase tracking-[0.4em] mb-2 block italic transition-colors">Final Total</span>
            <span id="final-total" class="font-black text-5xl text-stone-900 dark:text-white tracking-tighter italic leading-none transition-colors" data-original="{{ $subtotal - $discountValue }}">
                PHP {{ number_format(max(0, $subtotal - $discountValue), 2) }}
            </span>
        </div>

        {{-- 🟢 FIX: The button completely flips its colors depending on the mode so it always pops --}}
        <button type="submit" class="group relative w-full font-black py-5 rounded-2xl shadow-xl uppercase tracking-[0.2em] text-[10px] transition-all active:scale-[0.98] bg-stone-900 text-white hover:bg-amber-500 dark:bg-white dark:text-stone-900 dark:hover:bg-amber-500 dark:hover:text-stone-950">
            Authorize Payment
        </button>
    </form>
</div>
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-24 text-center bg-white dark:bg-stone-900 rounded-[3rem] border border-stone-100 dark:border-stone-800 shadow-sm px-6">
                    <div class="w-24 h-24 bg-stone-50 dark:bg-stone-800 rounded-[2rem] flex items-center justify-center mb-8 shadow-inner"><svg class="w-10 h-10 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg></div>
                    <h3 class="text-3xl font-black text-stone-900 dark:text-white uppercase tracking-tight italic">Manifest void</h3>
                    <a href="{{ route('home') }}" class="mt-12 px-12 py-5 bg-amber-600 text-white font-black uppercase text-[10px] tracking-[0.3em] rounded-full shadow-xl transition transform hover:-translate-y-1 active:scale-95">Initialize Selection</a>
                </div>
            @endif
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cb      = document.getElementById('redeem-checkbox');
            const totalEl = document.getElementById('final-total');
            const row     = document.getElementById('discount-row');
            if (cb && totalEl) {
                const orig = parseFloat(totalEl.getAttribute('data-original'));
                cb.addEventListener('change', function() {
                    if (this.checked) {
                        let discount = orig * 0.01;
                        let val = Math.max(0, orig - discount);
                        totalEl.innerText = 'PHP ' + val.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        totalEl.classList.add('text-emerald-500');
                        if(row) row.classList.remove('hidden');
                        if(row) row.classList.add('flex');
                    } else {
                        totalEl.innerText = 'PHP ' + orig.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        totalEl.classList.remove('text-emerald-500');
                        if(row) row.classList.add('hidden');
                        if(row) row.classList.remove('flex');
                    }
                });
            }
        });
    </script>
</x-app-layout>