<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-stone-800 dark:text-white leading-tight">
            {{ __('Your Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Error:</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('cart') && count(session('cart')) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-2 space-y-4">
                        @foreach(session('cart') as $id => $details)
                            <div class="bg-white dark:bg-stone-900 p-4 rounded-2xl shadow-sm border border-stone-100 dark:border-stone-800 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-stone-100 dark:bg-stone-800 rounded-xl overflow-hidden shrink-0">
                                        @if(isset($details['image']))
                                            <img src="{{ asset('storage/' . $details['image']) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-2xl">☕</div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-stone-900 dark:text-white">{{ $details['name'] }}</h3>
                                        <p class="text-sm text-stone-500 dark:text-stone-400">₱{{ $details['price'] }} x {{ $details['quantity'] }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <button class="text-red-500 hover:text-red-700 font-bold text-sm bg-red-50 dark:bg-red-900/20 px-3 py-1 rounded-lg">Remove</button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-stone-900 p-6 rounded-2xl shadow-lg border border-stone-100 dark:border-stone-800 sticky top-24">
                            <h3 class="font-bold text-xl text-stone-900 dark:text-white mb-6">Order Summary</h3>
                            
                            @php
                                $total = 0;
                                foreach(session('cart') as $details) {
                                    $total += $details['price'] * $details['quantity'];
                                }
                                $userPoints = Auth::user()->points;
                                $canRedeem = $userPoints >= 50; 
                                // 🟢 NEW FEATURE: Calculate points to be earned (1 point per ₱100)
                                $pointsToEarn = floor($total / 100);
                            @endphp

                            <div class="space-y-2 mb-6 text-stone-600 dark:text-stone-300">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span>₱{{ number_format($total, 2) }}</span>
                                </div>
                                <div id="discount-row" class="justify-between text-green-600 font-bold" style="display: none;">
                                    <span>Loyalty Discount</span>
                                    <span>-₱50.00</span>
                                </div>
                                <div class="flex justify-between text-amber-600 text-xs font-bold pt-2 border-t border-stone-50 dark:border-stone-800">
                                    <span>Points to earn:</span>
                                    <span>+{{ $pointsToEarn }} PTS</span>
                                </div>
                            </div>

                            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                                @csrf
                                
                                <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-xl mb-6 border border-amber-100 dark:border-amber-800/30">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-bold text-amber-800 dark:text-amber-500 flex items-center gap-2">
                                            <span>⭐</span> Redeem Points
                                        </span>
                                        <span class="text-xs font-bold bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-200 px-2 py-1 rounded-full">
                                            Balance: {{ $userPoints }}
                                        </span>
                                    </div>

                                    @if($canRedeem)
                                        <label class="flex items-center gap-3 cursor-pointer select-none">
                                            <input type="checkbox" id="redeem-checkbox" name="redeem_points" value="1" class="w-5 h-5 rounded text-amber-600 focus:ring-amber-500 border-gray-300">
                                            <div class="text-sm text-stone-600 dark:text-stone-300">
                                                Use <strong>50 Points</strong> for <br>
                                                <span class="text-green-600 dark:text-green-400 font-bold">₱50.00 OFF</span>
                                            </div>
                                        </label>
                                    @else
                                        <p class="text-xs text-stone-500 dark:text-stone-400 italic">
                                            Earn 50 points to unlock discount.
                                        </p>
                                    @endif
                                </div>

                                <div class="border-t border-stone-100 dark:border-stone-800 pt-4 mb-6">
                                    <div class="flex justify-between items-end">
                                        <span class="font-bold text-stone-900 dark:text-white text-lg">Total to Pay</span>
                                        <span id="final-total" class="font-extrabold text-2xl text-stone-900 dark:text-white" 
                                              data-original="{{ $total }}">
                                            ₱{{ number_format($total, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-stone-900 dark:bg-white text-white dark:text-stone-900 font-bold py-4 rounded-xl hover:shadow-lg hover:scale-[1.02] transition transform duration-200">
                                    Place Order
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="max-w-xl mx-auto">
                    <div class="bg-white dark:bg-stone-900 rounded-3xl shadow-sm border border-stone-100 dark:border-stone-800 p-12 text-center">
                        <h3 class="text-2xl font-bold text-stone-900 dark:text-white mb-2">Your cart is empty.</h3>
                        <p class="text-stone-500 dark:text-stone-400 mb-8">Looks like you haven't added any coffee yet!</p>
                        <a href="{{ route('home') }}" class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:scale-105 transition transform">
                            Browse Menu
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

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
                        totalElement.innerText = '₱' + newTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        totalElement.classList.add('text-green-600');
                        // 🟢 FIXED JS: Correctly managing display property
                        if(discountRow) discountRow.style.display = 'flex';
                    } else {
                        totalElement.innerText = '₱' + originalTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        totalElement.classList.remove('text-green-600');
                        if(discountRow) discountRow.style.display = 'none';
                    }
                });
            }
        });
    </script>
</x-app-layout>