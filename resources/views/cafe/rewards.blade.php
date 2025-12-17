<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-stone-800 dark:text-white leading-tight">
            {{ __('My Rewards') }}
        </h2>
    </x-slot>

    @php
        $points = Auth::user()->points;
        $target = 100; 
        $progress = min(($points / $target) * 100, 100);
        $remaining = max(0, $target - $points);
        // Fetch History
        $history = App\Models\Order::where('user_id', Auth::id())
                    ->where(function($q) {
                        $q->where('points_earned', '>', 0)
                          ->orWhere('points_redeemed', '>', 0);
                    })
                    ->latest()
                    ->get();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-stone-900 rounded-3xl p-8 md:p-12 text-center text-white mb-12 shadow-2xl relative overflow-hidden border border-stone-800">
                <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                    <div class="absolute right-0 top-0 transform translate-x-1/3 -translate-y-1/3 text-[20rem]">☕</div>
                </div>
                <p class="text-stone-400 text-lg uppercase tracking-widest font-bold mb-4">Current Balance</p>
                <h1 class="text-7xl md:text-9xl font-extrabold text-amber-500 mb-6 drop-shadow-lg">{{ $points }}</h1>
                <p class="text-2xl font-light text-stone-200">Star Points</p>
                <div class="max-w-xl mx-auto mt-10">
                    <div class="flex justify-between text-sm text-stone-400 mb-2 font-medium"><span>0</span><span>Goal: {{ $target }}</span></div>
                    <div class="w-full bg-stone-800 rounded-full h-4 overflow-hidden border border-stone-700">
                        <div class="bg-amber-600 h-4 rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(217,119,6,0.5)]" x-data="{ w: {{ $progress }} }" x-bind:style="'width: ' + w + '%'"></div>
                    </div>
                    <p class="mt-4 text-stone-300 text-sm md:text-base">
                        @if($points >= $target) 🎉 You have enough points for a <strong>Free Coffee!</strong> @else You are <strong class="text-white">{{ $remaining }} points</strong> away from your next reward! @endif
                    </p>
                </div>
            </div>

            <h3 class="text-2xl font-bold text-stone-900 dark:text-white mb-6">Rewards Catalog</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-16">
                
                <form action="{{ route('rewards.claim') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="voucher">
                    <input type="hidden" name="name" value="₱50 Off Voucher">
                    <input type="hidden" name="points" value="50">
                    <input type="hidden" name="value" value="50">
                    <div class="bg-white dark:bg-stone-900 rounded-2xl p-6 border-2 {{ $points >= 50 ? 'border-amber-500 shadow-md' : 'border-stone-100 dark:border-stone-800 opacity-60' }} transition group h-full flex flex-col">
                        <div class="flex justify-between items-start mb-4"><span class="bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-300 px-3 py-1 rounded-full text-xs font-bold uppercase">50 Stars</span></div>
                        <h4 class="text-xl font-bold text-stone-900 dark:text-white mb-2">₱50 Off Voucher</h4>
                        <p class="text-stone-500 dark:text-stone-400 text-sm mb-6 flex-1">Get ₱50 off your total bill instantly.</p>
                        <button type="submit" class="w-full py-3 rounded-xl font-bold text-sm transition {{ $points >= 50 ? 'bg-amber-600 text-white hover:bg-amber-700' : 'bg-stone-100 text-stone-400 cursor-not-allowed' }}" {{ $points < 50 ? 'disabled' : '' }}>
                            {{ $points >= 50 ? 'Redeem at Checkout' : 'Need more points' }}
                        </button>
                    </div>
                </form>

                <form action="{{ route('rewards.claim') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="free_item">
                    <input type="hidden" name="name" value="Free Tall Drink">
                    <input type="hidden" name="points" value="100">
                    <input type="hidden" name="value" value="120"> <div class="bg-white dark:bg-stone-900 rounded-2xl p-6 border-2 {{ $points >= 100 ? 'border-amber-500 shadow-md' : 'border-stone-100 dark:border-stone-800 opacity-60' }} transition group h-full flex flex-col">
                        <div class="flex justify-between items-start mb-4"><span class="bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-300 px-3 py-1 rounded-full text-xs font-bold uppercase">100 Stars</span></div>
                        <h4 class="text-xl font-bold text-stone-900 dark:text-white mb-2">Free Tall Drink</h4>
                        <p class="text-stone-500 dark:text-stone-400 text-sm mb-6 flex-1">Redeem any Tall (12oz) beverage.</p>
                        <button type="submit" class="w-full py-3 rounded-xl font-bold text-sm transition {{ $points >= 100 ? 'bg-amber-600 text-white hover:bg-amber-700' : 'bg-stone-100 text-stone-400 cursor-not-allowed' }}" {{ $points < 100 ? 'disabled' : '' }}>
                            {{ $points >= 100 ? 'Redeem at Checkout' : 'Need more points' }}
                        </button>
                    </div>
                </form>

                <form action="{{ route('rewards.claim') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="free_item">
                    <input type="hidden" name="name" value="Free Rice Meal">
                    <input type="hidden" name="points" value="150">
                    <input type="hidden" name="value" value="150">
                    <div class="bg-white dark:bg-stone-900 rounded-2xl p-6 border-2 {{ $points >= 150 ? 'border-amber-500 shadow-md' : 'border-stone-100 dark:border-stone-800 opacity-60' }} transition group h-full flex flex-col">
                        <div class="flex justify-between items-start mb-4"><span class="bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-300 px-3 py-1 rounded-full text-xs font-bold uppercase">150 Stars</span></div>
                        <h4 class="text-xl font-bold text-stone-900 dark:text-white mb-2">Free Rice Meal</h4>
                        <p class="text-stone-500 dark:text-stone-400 text-sm mb-6 flex-1">Enjoy a Chicken Teriyaki or Katsudon on us.</p>
                        <button type="submit" class="w-full py-3 rounded-xl font-bold text-sm transition {{ $points >= 150 ? 'bg-amber-600 text-white hover:bg-amber-700' : 'bg-stone-100 text-stone-400 cursor-not-allowed' }}" {{ $points < 150 ? 'disabled' : '' }}>
                            {{ $points >= 150 ? 'Redeem at Checkout' : 'Need more points' }}
                        </button>
                    </div>
                </form>

            </div>

            <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-stone-100 dark:border-stone-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-100 dark:border-stone-800">
                    <h3 class="text-lg font-bold text-stone-900 dark:text-white">Points History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-stone-50 dark:bg-stone-800 text-stone-500 dark:text-stone-400 text-xs uppercase">
                            <tr>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Activity</th>
                                <th class="px-6 py-3 text-right">Points</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                            @forelse($history as $item)
                                <tr class="text-sm text-stone-700 dark:text-stone-300">
                                    <td class="px-6 py-4">{{ $item->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        @if($item->points_redeemed > 0)
                                            <span class="text-red-600">Redeemed: {{ $item->reward_type }}</span>
                                        @else
                                            <span>Order #{{ $item->id }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold">
                                        @if($item->points_redeemed > 0)
                                            <span class="text-red-600">-{{ $item->points_redeemed }}</span>
                                        @endif
                                        @if($item->points_earned > 0)
                                            <span class="text-green-600">+{{ $item->points_earned }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-stone-500">No history yet. Start ordering!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>