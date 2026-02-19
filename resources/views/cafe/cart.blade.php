<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl md:text-3xl text-stone-900 dark:text-white uppercase tracking-tight">
                {{ __('Your Cart') }}
            </h2>
            <div class="flex items-center gap-2 px-4 py-1.5 bg-stone-100 dark:bg-stone-800 rounded-full border border-stone-200 dark:border-stone-700">
                <span class="text-[10px] font-black uppercase text-stone-500 tracking-widest">Items</span>
                <span class="text-xs font-black text-amber-600">{{ session('cart') ? count(session('cart')) : 0 }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-8 p-5 bg-white dark:bg-stone-900 border-l-4 border-red-600 shadow-sm rounded-r-2xl overflow-hidden animate-pulse">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-red-600">Transaction Error</h3>
                            <p class="text-[11px] text-stone-500 dark:text-stone-400 font-medium mt-1 uppercase tracking-widest">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('cart') && count(session('cart')) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    
                    {{-- Cart Items Section --}}
                    <div class="lg:col-span-2 space-y-6">
                        @foreach(session('cart') as $id => $details)
                            <div class="group bg-white dark:bg-stone-900 p-6 rounded-[2rem] shadow-sm border border-stone-100 dark:border-stone-800 flex flex-col sm:flex-row items-center justify-between transition-all duration-300 hover:shadow-xl hover:border-amber-500/20">
                                <div class="flex items-center gap-6 w-full">
                                    <div class="relative w-24 h-24 bg-stone-100 dark:bg-stone-800 rounded-3xl overflow-hidden shrink-0 border border-stone-200 dark:border-stone-700 shadow-inner">
                                        @if(isset($details['image']))
                                            <img src="{{ asset('storage/' . $details['image']) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-black text-lg text-stone-900 dark:text-white uppercase tracking-tight">{{ $details['name'] }}</h3>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-xs font-bold text-amber-600">PHP {{ number_format($details['price'], 2) }}</span>
                                            <span class="w-1 h-1 bg-stone-300 dark:bg-stone-700 rounded-full"></span>
                                            <span class="text-[10px] font-black text-stone-400 uppercase tracking-widest">Qty: {{ $details['quantity'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between w-full sm:w-auto mt-6 sm:mt-0 gap-6">
                                    <div class="text-right hidden sm:block">
                                        <p class="text-[8px] font-black text-stone-400 uppercase tracking-widest mb-0.5">Line Total</p>
                                        <p class="font-black text-stone-900 dark:text-white">PHP {{ number_format($details['price'] * $details['quantity'], 2) }}</p>
                                    </div>
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button class="flex items-center justify-center w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-900/10 text-red-500 hover:bg-red-600 hover:text-white transition-all duration-300 shadow-sm active:scale-95">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Summary Section --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-stone-900 p-8 rounded-[2.5rem] shadow-2xl border border-stone-100 dark:border-stone-800 sticky top-24">
                            <h3 class="font-black text-xl text-stone-900 dark:text-white uppercase tracking-tight mb-8 pb-4 border-b border-stone-50 dark:border-stone-800">Order Summary</h3>
                            
                            @php
                                $subtotal = 0;
                                foreach(session('cart') as $details) { $subtotal += $details['price'] * $details['quantity']; }
                                $userPoints = Auth::user()->loyalty_points ?? 0;
                                $canRedeem = $userPoints >= 50; 
                                $pointsToEarn = 10; 
                            @endphp

                            <div class="space-y-4 mb-8">
                                <div class="flex justify-between items-center text-sm font-bold text-stone-500 uppercase tracking-widest">
                                    <span>Subtotal</span>
                                    <span class="text-stone-900 dark:text-white">PHP {{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div id="discount-row" class="justify-between text-green-600 font-black text-sm uppercase tracking-widest" style="display: none;">
                                    <span>Loyalty Discount</span>
                                    <span>-PHP 50.00</span>
                                </div>
                                <div class="p-4 bg-amber-500/5 rounded-2xl border border-amber-500/10 flex justify-between items-center">
                                    <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Points to earn:</span>
                                    <span class="text-xs font-black text-amber-600">+{{ $pointsToEarn }} PTS</span>
                                </div>
                            </div>

                            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                                @csrf
                                <div class="bg-stone-50 dark:bg-stone-800/50 p-6 rounded-3xl mb-8 border border-stone-200 dark:border-stone-700">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="font-black text-stone-400 uppercase text-[10px] tracking-[0.2em]">Redeem Points</span>
                                        <span class="text-[10px] font-black bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 px-3 py-1 rounded-full border border-amber-200 dark:border-amber-800">
                                            Balance: {{ $userPoints }}
                                        </span>
                                    </div>

                                    @if($canRedeem)
                                        <label class="flex items-start gap-4 cursor-pointer select-none group">
                                            <div class="relative mt-1">
                                                <input type="checkbox" id="redeem-checkbox" name="redeem_points" value="1" class="peer sr-only">
                                                <div class="w-6 h-6 border-2 border-stone-300 dark:border-stone-600 rounded-lg peer-checked:bg-amber-600 peer-checked:border-amber-600 transition-all flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            </div>
                                            <div class="text-xs text-stone-600 dark:text-stone-400 font-medium leading-relaxed">
                                                Use <span class="font-black text-stone-900 dark:text-white">50 Points</span> for <br>
                                                <span class="text-green-600 dark:text-green-400 font-black uppercase">PHP 50.00 OFF</span>
                                            </div>
                                        </label>
                                    @else
                                        <div class="flex items-center gap-2 text-[10px] text-stone-500 dark:text-stone-400 italic">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Earn 50 points to unlock discount.
                                        </div>
                                    @endif
                                </div>

                                <div class="pt-2 mb-8">
                                    <div class="flex justify-between items-end">
                                        <span class="font-black text-stone-400 uppercase text-[10px] tracking-[0.2em] mb-1.5">Total to Pay</span>
                                        <span id="final-total" class="font-black text-3xl text-stone-900 dark:text-white tracking-tighter" data-original="{{ $subtotal }}">
                                            PHP {{ number_format($subtotal, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <button type="submit" class="group relative w-full bg-stone-900 dark:bg-white text-white dark:text-stone-900 font-black py-5 rounded-2xl shadow-xl uppercase tracking-[0.2em] text-xs transition-all duration-300 hover:bg-amber-600 dark:hover:bg-amber-500 hover:text-white active:scale-[0.98]">
                                    <span class="relative z-10 flex items-center justify-center gap-3">
                                        Place Order
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-24 text-center bg-white dark:bg-stone-900 rounded-[3rem] border border-stone-100 dark:border-stone-800 shadow-sm px-6">
                    <div class="w-24 h-24 bg-stone-50 dark:bg-stone-800 rounded-[2rem] flex items-center justify-center mb-8 shadow-inner">
                        <svg class="w-10 h-10 text-stone-300 dark:text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-stone-900 dark:text-white uppercase tracking-tight">Your cart is empty</h3>
                    <p class="text-stone-500 dark:text-stone-400 mt-2 font-medium max-w-xs mx-auto italic">Looks like you haven't added any of our handcrafted roasts yet.</p>
                    <a href="{{ route('home') }}" class="mt-10 px-10 py-4 bg-amber-600 text-white font-black uppercase text-[10px] tracking-[0.2em] rounded-full shadow-xl shadow-amber-600/20 transition transform hover:-translate-y-1 active:scale-95">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
<!-- js , javascript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('redeem-checkbox');
            const totalElement = document.getElementById('final-total');
            const discountRow = document.getElementById('discount-row');
            
            if (checkbox && totalElement) {
                const originalTotal = parseFloat(totalElement.getAttribute('data-original'));
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        let newTotal = Math.max(0, originalTotal - 50);
                        totalElement.innerText = 'PHP ' + newTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        totalElement.classList.add('text-green-600');
                        if(discountRow) discountRow.style.display = 'flex';
                    } else {
                        totalElement.innerText = 'PHP ' + originalTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        totalElement.classList.remove('text-green-600');
                        if(discountRow) discountRow.style.display = 'none';
                    }
                });
            }
        });
    </script>
</x-app-layout>