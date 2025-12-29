<x-app-layout>
    @php
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $points = $user->points ?? 0; 
        
        $target = 100;
        if ($points >= 100) $target = 200;
        if ($points >= 200) $target = 500;
        $progress = min(($points / $target) * 100, 100);
        
        $history = \App\Models\PointTransaction::where('user_id', Auth::id())->latest()->get();

        $tier = 'Bronze';
        if($points >= 500) $tier = 'Gold';
        elseif($points >= 200) $tier = 'Silver';

        $availableRewards = [
            ['id' => 'free_espresso', 'name' => 'Signature Espresso', 'cost' => 50],
            ['id' => 'pastry_treat', 'name' => 'Artisan Pastry', 'cost' => 80],
            ['id' => 'premium_brew', 'name' => 'Large Premium Brew', 'cost' => 120],
            ['id' => 'bag_beans', 'name' => 'House Blend (250g)', 'cost' => 500],
        ];
    @endphp

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500" 
         x-data="{ 
            currentProgress: 0, 
            redeemModal: false, 
            reward: { id: '', name: '', cost: 0 } 
         }" 
         x-init="setTimeout(() => currentProgress = {{ $progress }}, 500)">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- 1. Main Rewards Command Card --}}
            <div class="bg-stone-900 rounded-[3rem] p-8 md:p-16 text-center text-white mb-12 shadow-2xl relative overflow-hidden border border-stone-800">
                <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full blur-[100px] -mr-32 -mt-32"></div>
                <div class="mb-10 inline-flex items-center gap-3 px-6 py-2.5 rounded-full bg-amber-500/10 border border-amber-500/20">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-500">{{ $tier }} Tier Status</span>
                </div>
                <p class="text-stone-500 text-sm uppercase tracking-[0.5em] font-black mb-6 italic">Star Points Balance</p>
                <h1 class="text-9xl md:text-[14rem] font-black text-amber-500 mb-12 drop-shadow-[0_10px_30px_rgba(245,158,11,0.3)] italic tracking-tighter leading-none">{{ $points }}</h1>
                
                <div class="max-w-xl mx-auto mt-12">
                    <div class="flex justify-between text-[9px] font-black text-stone-500 mb-5 uppercase tracking-[0.3em] px-2">
                        <span>0 Balance</span>
                        <span class="text-stone-300">Goal: {{ $target }} PTS</span>
                    </div>
                    <div class="w-full bg-stone-950 rounded-full h-3 overflow-hidden border border-stone-800 p-0.5 shadow-inner">
                        <div class="bg-gradient-to-r from-amber-600 to-amber-400 h-full rounded-full transition-all duration-[2.5s] ease-out shadow-[0_0_15px_rgba(217,119,6,0.5)]" 
                             :style="`width: ${currentProgress}%`" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>

            {{-- 2. Available Rewards Vault --}}
            <div class="mb-16">
                <div class="flex items-center justify-between px-2 mb-8">
                    <h3 class="font-black text-stone-900 dark:text-white uppercase tracking-tighter italic text-2xl">Redemption Vault</h3>
                    <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest bg-amber-500/10 px-3 py-1 rounded-full border border-amber-500/20">Unlocked Rewards Only</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($availableRewards as $r)
                        <div class="bg-white dark:bg-stone-900 rounded-[2.5rem] p-8 border border-stone-100 dark:border-stone-800 shadow-xl flex flex-col items-center text-center transition-all hover:scale-[1.03] group {{ $points < $r['cost'] ? 'opacity-50 grayscale' : '' }}">
                            <h4 class="font-black text-stone-900 dark:text-white uppercase italic text-sm mb-1 mt-4">{{ $r['name'] }}</h4>
                            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-6">{{ $r['cost'] }} PTS</p>
                            
                            @if($points >= $r['cost'])
                                <button @click="reward = { id: '{{ $r['id'] }}', name: '{{ $r['name'] }}', cost: {{ $r['cost'] }} }; redeemModal = true" 
                                        class="w-full py-3 bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-900 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg hover:bg-amber-600 dark:hover:bg-amber-500 transition-colors">
                                    Redeem Now
                                </button>
                            @else
                                <div class="w-full py-3 bg-stone-100 dark:bg-stone-800 text-stone-400 rounded-xl text-[9px] font-black uppercase tracking-widest cursor-not-allowed">
                                    {{ $r['cost'] - $points }} More Required
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 3. Stats Summary Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-stone-900 p-10 rounded-[2.5rem] border border-stone-100 dark:border-stone-800 shadow-xl">
                    <p class="text-[10px] font-black uppercase tracking-widest text-stone-500 mb-4">Milestones</p>
                    <p class="text-4xl font-black text-stone-900 dark:text-white italic tracking-tighter">{{ $history->where('amount', '>', 0)->sum('amount') }} <span class="text-xs text-stone-400 uppercase tracking-widest not-italic ml-2">Total Earned</span></p>
                </div>
                <div class="bg-white dark:bg-stone-900 p-10 rounded-[2.5rem] border border-stone-100 dark:border-stone-800 shadow-xl">
                    <p class="text-[10px] font-black uppercase tracking-widest text-stone-500 mb-4">Used</p>
                    <p class="text-4xl font-black text-stone-900 dark:text-white italic tracking-tighter">{{ abs($history->where('amount', '<', 0)->sum('amount')) }} <span class="text-xs text-stone-400 uppercase tracking-widest not-italic ml-2">Redeemed</span></p>
                </div>
                <div class="bg-amber-600 p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black uppercase tracking-widest text-stone-950 mb-4">Latest Activity</p>
                        <p class="text-lg font-black text-stone-950 uppercase italic leading-tight truncate">{{ $history->first()->description ?? 'No recent activity' }}</p>
                        <p class="text-[9px] font-bold text-stone-900/60 uppercase mt-2">{{ $history->first() ? $history->first()->created_at->diffForHumans() : 'Terminal Idle' }}</p>
                    </div>
                </div>
            </div>

            {{-- 4. Points History Terminal Logs --}}
            <div class="mt-16 bg-white dark:bg-stone-900 rounded-[3rem] border border-stone-100 dark:border-stone-800 shadow-2xl overflow-hidden">
                <div class="p-8 sm:p-12 border-b border-stone-50 dark:border-stone-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-2xl font-black text-stone-900 dark:text-white uppercase italic tracking-tighter leading-none mb-2">Terminal Logs</h3>
                        <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em]">Authorized Loyalty Ledger</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50 dark:bg-stone-950/50 text-stone-400 text-[9px] font-black uppercase tracking-[0.3em]">
                                <th class="px-8 py-6">Date and Status</th>
                                <th class="px-8 py-6">Description</th>
                                <th class="px-8 py-6 text-right">Magnitude</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-50 dark:divide-stone-800">
                            @forelse($history as $transaction)
                                <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/30 transition-colors group">
                                    <td class="px-8 py-8 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="w-2 h-2 rounded-full {{ $transaction->amount > 0 ? 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.4)]' : 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.4)]' }}"></div>
                                            <div>
                                                <p class="text-[11px] font-black text-stone-900 dark:text-white uppercase tracking-tighter">{{ $transaction->created_at->format('M d, Y') }}</p>
                                                <p class="text-[9px] font-bold text-stone-400 uppercase tracking-widest">{{ $transaction->created_at->format('h:i A') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-8">
                                        <p class="text-sm font-medium text-stone-600 dark:text-stone-300 italic group-hover:text-stone-900 dark:group-hover:text-white transition-colors">{{ $transaction->description }}</p>
                                    </td>
                                    <td class="px-8 py-8 text-right whitespace-nowrap">
                                        <div class="inline-flex items-baseline gap-1">
                                            <span class="text-xl font-black {{ $transaction->amount > 0 ? 'text-amber-600' : 'text-rose-500' }} italic">{{ $transaction->amount > 0 ? '+' : '' }}{{ $transaction->amount }}</span>
                                            <span class="text-[9px] font-black text-stone-400 uppercase">PTS</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-8 py-24 text-center opacity-30 font-black uppercase text-[10px]">No logs synchronized</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 5. Redemption Modal --}}
        <div x-show="redeemModal" 
             x-cloak 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-stone-950/90 backdrop-blur-md">
            
            <div class="bg-white dark:bg-stone-900 w-full max-w-lg rounded-[3.5rem] border border-stone-200 dark:border-stone-800 shadow-2xl overflow-hidden p-10 sm:p-14" @click.away="redeemModal = false">
                <div class="text-center mb-10">
                    <span class="text-[10px] font-black text-amber-600 uppercase tracking-[0.4em] mb-4 block italic">Authorized Redemption</span>
                    <h3 class="text-3xl font-black text-stone-900 dark:text-white uppercase italic tracking-tighter leading-none" x-text="reward.name"></h3>
                </div>

                <div class="bg-stone-50 dark:bg-stone-950 p-8 rounded-[2rem] border border-stone-100 dark:border-stone-800 mb-10 text-center">
                    <p class="text-xs text-stone-500 uppercase font-black tracking-widest mb-2">Cost to Balance</p>
                    <p class="text-4xl font-black text-stone-900 dark:text-white italic tracking-tighter">-<span x-text="reward.cost"></span> <span class="text-sm not-italic text-stone-400">PTS</span></p>
                </div>

                <form action="{{ route('rewards.claim') }}" method="POST">
                    @csrf
                    <input type="hidden" name="reward_id" :value="reward.id">
                    <div class="flex flex-col gap-4">
                        <button type="submit" class="w-full py-6 bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-900 rounded-[2rem] font-black uppercase tracking-[0.2em] shadow-2xl transition-transform hover:scale-[1.02]">Confirm Transaction</button>
                        <button type="button" @click="redeemModal = false" class="text-[9px] font-black uppercase text-stone-400 tracking-widest hover:text-stone-900 dark:hover:text-white transition-colors">Abort Command</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>