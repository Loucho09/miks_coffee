<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🛒 {{ __('Your Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 transition-colors">
                
                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Error:</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('cart') && count(session('cart')) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6">Product</th>
                                    <th class="py-3 px-6 text-center">Price</th>
                                    <th class="py-3 px-6 text-center">Qty</th>
                                    <th class="py-3 px-6 text-center">Total</th>
                                    <th class="py-3 px-6 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light">
                                @php $total = 0; @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity']; @endphp
                                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="py-3 px-6 font-bold">{{ $details['name'] }}</td>
                                        <td class="py-3 px-6 text-center">₱{{ number_format($details['price'], 2) }}</td>
                                        <td class="py-3 px-6 text-center">{{ $details['quantity'] }}</td>
                                        <td class="py-3 px-6 text-center font-bold">₱{{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                        <td class="py-3 px-6 text-center">
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button class="text-red-500 font-bold hover:underline text-xs uppercase">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg w-full md:w-1/3">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Summary</h3>
                            
                            <div class="flex justify-between mb-2 text-gray-600 dark:text-gray-300">
                                <span>Subtotal</span>
                                <span>₱{{ number_format($total, 2) }}</span>
                            </div>
                            <div id="discount-row" class="flex justify-between mb-2 text-green-600 font-bold hidden">
                                <span>Discount</span>
                                <span>-₱50.00</span>
                            </div>

                            <div class="border-t border-gray-300 dark:border-gray-600 my-2"></div>

                            <form action="{{ route('checkout.store') }}" method="POST">
                                @csrf
                                
                                @php 
                                    $userPoints = Auth::user()->points; 
                                    $canRedeem = $userPoints >= 50; 
                                @endphp

                                <div class="mb-4 bg-amber-100 dark:bg-amber-900/30 p-3 rounded-lg border border-amber-200 dark:border-amber-800">
                                    <div class="flex justify-between font-bold text-amber-800 dark:text-amber-500 mb-2">
                                        <span>Points: {{ $userPoints }}</span>
                                    </div>
                                    @if($canRedeem)
                                        <label class="flex items-center gap-2 cursor-pointer select-none">
                                            <input type="checkbox" id="redeem-checkbox" name="redeem_points" value="1" class="rounded text-amber-600 focus:ring-amber-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Use 50 pts for ₱50 OFF</span>
                                        </label>
                                    @else
                                        <span class="text-xs text-gray-500 dark:text-gray-400 italic">Need 50 points to redeem.</span>
                                    @endif
                                </div>

                                <div class="flex justify-between mb-6 text-lg font-bold text-gray-900 dark:text-white">
                                    <span>Total</span>
                                    <span id="final-total" data-original="{{ $total }}">₱{{ number_format($total, 2) }}</span>
                                </div>
                                
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-lg">
                                    Checkout
                                </button>
                            </form>
                            </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Cart is empty.</p>
                        <a href="{{ route('home') }}" class="text-blue-500 underline">Browse Menu</a>
                    </div>
                @endif
            </div>
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
                        if(discountRow) discountRow.classList.remove('hidden');
                    } else {
                        totalElement.innerText = '₱' + originalTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        if(discountRow) discountRow.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</x-app-layout>