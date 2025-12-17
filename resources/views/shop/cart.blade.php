<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-stone-800 dark:text-white leading-tight">
            Shopping Cart
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="flex-1 bg-white dark:bg-[#1C1C1E] overflow-hidden shadow-sm sm:rounded-3xl p-6">
                    @if(session('cart') && count(session('cart')) > 0)
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-stone-500 dark:text-stone-400 border-b border-stone-200 dark:border-stone-800 text-sm uppercase tracking-wider">
                                    <th class="py-4 font-medium">Product</th>
                                    <th class="py-4 font-medium hidden md:table-cell">Price</th>
                                    <th class="py-4 font-medium">Qty</th>
                                    <th class="py-4 font-medium">Total</th>
                                    <th class="py-4 font-medium"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(session('cart') as $id => $details)
                                    <tr class="border-b border-stone-100 dark:border-stone-800 last:border-0 group">
                                        <td class="py-4">
                                            <div class="flex items-center gap-4">
                                                @if($details['image'])
                                                    <img src="{{ asset('storage/' . $details['image']) }}" class="w-16 h-16 object-cover rounded-xl shadow-sm">
                                                @else
                                                    <div class="w-16 h-16 bg-stone-100 dark:bg-stone-800 rounded-xl flex items-center justify-center text-xs text-stone-400">No Img</div>
                                                @endif
                                                
                                                <div>
                                                    <div class="font-bold text-lg text-stone-900 dark:text-white leading-tight">{{ $details['name'] }}</div>
                                                    @if(isset($details['size']) && $details['size'])
                                                        <div class="text-[10px] uppercase font-bold text-stone-500 bg-stone-100 dark:bg-stone-800 px-2 py-0.5 rounded inline-block mt-1">
                                                            {{ $details['size'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 font-medium text-stone-600 dark:text-stone-400 hidden md:table-cell">
                                            ₱{{ number_format($details['price'], 2) }}
                                        </td>
                                        <td class="py-4">
                                            <span class="font-bold text-stone-900 dark:text-white bg-stone-100 dark:bg-stone-800 px-3 py-1 rounded-lg">
                                                {{ $details['quantity'] }}
                                            </span>
                                        </td>
                                        <td class="py-4 font-bold text-amber-600">
                                            ₱{{ number_format($details['price'] * $details['quantity'], 2) }}
                                        </td>
                                        <td class="py-4 text-right">
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button type="submit" class="text-red-400 hover:text-red-600 transition p-2 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-20">
                            <div class="text-6xl mb-4 opacity-30">🛒</div>
                            <h3 class="text-xl font-bold text-stone-400 mb-4">Your cart is empty</h3>
                            <a href="{{ route('home') }}" class="px-6 py-3 bg-amber-500 text-white rounded-xl font-bold hover:bg-amber-600 transition shadow-lg shadow-amber-500/20">
                                Browse Menu
                            </a>
                        </div>
                    @endif
                </div>

                @if(session('cart') && count(session('cart')) > 0)
                    <div class="w-full lg:w-96">
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            
                            <div class="bg-white dark:bg-[#1C1C1E] p-6 rounded-3xl shadow-sm h-fit sticky top-6">
                                <h3 class="font-bold text-xl text-stone-900 dark:text-white mb-6">Order Summary</h3>

                                <div class="mb-6">
                                    @if(session('claimed_reward'))
                                        <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 p-4 rounded-xl flex justify-between items-center">
                                            <div>
                                                <span class="block font-bold text-green-700 dark:text-green-400 text-sm">Reward Applied!</span>
                                                <span class="text-xs text-green-600 dark:text-green-500">{{ session('claimed_reward')['name'] }}</span>
                                            </div>
                                            <span class="font-bold text-green-700 dark:text-green-400">-₱{{ session('claimed_reward')['value'] }}</span>
                                        </div>
                                    @elseif(Auth::user()->points >= 50)
                                        <label class="flex items-start gap-3 p-4 border border-stone-200 dark:border-stone-700 rounded-xl cursor-pointer hover:bg-stone-50 dark:hover:bg-stone-800 transition">
                                            <input type="checkbox" name="redeem_points" value="1" class="mt-1 w-5 h-5 text-amber-500 rounded focus:ring-amber-500 border-gray-300">
                                            <div>
                                                <span class="block font-bold text-stone-900 dark:text-white text-sm">Redeem 50 Points</span>
                                                <span class="text-xs text-stone-500">Use 50 pts for a ₱50.00 discount</span>
                                            </div>
                                        </label>
                                    @else
                                        <div class="p-4 bg-stone-50 dark:bg-stone-800 rounded-xl text-center">
                                            <span class="text-xs font-bold text-stone-400 uppercase tracking-wide">Your Points</span>
                                            <div class="text-xl font-extrabold text-amber-500">{{ Auth::user()->points }} pts</div>
                                            <div class="text-xs text-stone-400 mt-1">Need 50 pts to redeem</div>
                                        </div>
                                    @endif
                                </div>

                                @php
                                    $total = 0;
                                    foreach(session('cart') as $details) {
                                        $total += $details['price'] * $details['quantity'];
                                    }
                                    
                                    $discount = 0;
                                    if(session('claimed_reward')) {
                                        $discount = session('claimed_reward')['value'];
                                    }
                                @endphp

                                <div class="space-y-3 mb-6 pb-6 border-b border-stone-100 dark:border-stone-800">
                                    <div class="flex justify-between text-stone-500 dark:text-stone-400">
                                        <span>Subtotal</span>
                                        <span>₱{{ number_format($total, 2) }}</span>
                                    </div>
                                    @if($discount > 0)
                                        <div class="flex justify-between text-green-600 font-bold">
                                            <span>Discount</span>
                                            <span>-₱{{ number_format($discount, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between text-xl font-extrabold text-stone-900 dark:text-white pt-2">
                                        <span>Total</span>
                                        <span>₱{{ number_format(max(0, $total - $discount), 2) }}</span>
                                    </div>
                                </div>

                                <div class="space-y-4 mb-6">
                                    <div>
                                        <label class="text-xs font-bold text-stone-500 uppercase">Customer Name</label>
                                        <input type="text" name="customer_name" required 
                                               value="{{ Auth::user()->name }}"
                                               class="w-full mt-1 px-4 py-2 rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-stone-500 uppercase">Email Receipt</label>
                                        <input type="email" name="customer_email" required 
                                               value="{{ Auth::user()->email }}"
                                               class="w-full mt-1 px-4 py-2 rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                                    </div>
                                    <input type="hidden" name="payment_method" value="cash">
                                </div>

                                <button type="submit" class="w-full py-4 bg-amber-500 hover:bg-amber-600 text-white font-bold text-lg rounded-2xl shadow-lg shadow-amber-500/30 transition transform hover:scale-[1.02] active:scale-95">
                                    Confirm Order
                                </button>
                                
                            </div>
                        </form>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>