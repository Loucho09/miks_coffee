<x-app-layout>
    @php
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $points = $user->loyalty_points ?? 0; 
        
        // 🟢 DISCOUNT POWER MANIFEST
        $pwrTiers = [
            ['id' => 'pwr_2',  'pts' => 200,  'label' => '2% Discount'],
            ['id' => 'pwr_5',  'pts' => 500,  'label' => '5% Discount'],
            ['id' => 'pwr_10', 'pts' => 1000, 'label' => '10% Discount'],
            ['id' => 'pwr_20', 'pts' => 2000, 'label' => '20% Discount (MAX)'],
        ];

        // Progress logic: target nearest locked milestone
        $target = 200;
        foreach($pwrTiers as $t) {
            if($points < $t['pts']) { $target = $t['pts']; break; }
            $target = 2000;
        }
        $progress = min(($points / $target) * 100, 100);
        
        $tier = $user->loyalty_tier;
        $tierMultiplierLabel = $tier === 'Gold' ? '2x' : ($tier === 'Silver' ? '1.5x' : '1x');
    @endphp

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500" 
         x-data="{ currentProgress: 0 }" 
         x-init="setTimeout(() => currentProgress = {{ $progress }}, 500)">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Loyalty Asset Dashboard --}}
            <div class="bg-stone-100 dark:bg-stone-900 rounded-[3rem] p-8 md:p-16 text-center text-white mb-12 shadow-2xl relative overflow-hidden border border-stone-800">
                <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full blur-[100px] -mr-32 -mt-32"></div>
                <div class="mb-10 inline-flex items-center gap-3 px-6 py-2.5 rounded-full bg-amber-500/10 border border-amber-500/20">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-500">{{ $tier }} Tier Status</span>
                    <span class="w-px h-3 bg-amber-500/30"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-400">{{ $tierMultiplierLabel }} Yield Active</span>
                </div>
                <p class="text-stone-500 text-sm uppercase tracking-[0.5em] font-black mb-6 italic">Star Points Assets</p>
                <h1 class="text-9xl md:text-[14rem] font-black text-amber-500 mb-12 drop-shadow-[0_10px_30px_rgba(245,158,11,0.3)] italic tracking-tighter leading-none">{{ number_format($points) }}</h1>
                
                <div class="max-w-xl mx-auto mt-12">
                    <div class="flex justify-between text-[9px] font-black text-stone-500 mb-5 uppercase tracking-[0.3em] px-2">
                        <span>Current Assets</span>
                        <span class="text-stone-500">Goal: {{ number_format($target) }} PTS</span>
                    </div>
                    <div class="w-full bg-stone-950 rounded-full h-3 overflow-hidden border border-stone-800 p-0.5 shadow-inner">
                        <div class="bg-gradient-to-r from-amber-600 to-amber-400 h-full rounded-full transition-all duration-[2.5s] ease-out shadow-[0_0_15px_rgba(217,119,6,0.5)]" 
                             :style="`width: ${currentProgress}%`" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="text-[8px] text-stone-600 uppercase font-black mt-6 tracking-[0.3em]">Protocol: 100 PTS = 1% OFF Total Manifest</p>
                </div>
            </div>

            {{-- 🟢 REDEMPTION VAULT: Discount Power --}}
            <div class="mb-16">
                <div class="flex items-center justify-between px-2 mb-8 text-left">
                    <h3 class="font-black text-stone-900 dark:text-white uppercase tracking-tighter italic text-2xl">Redemption Vault</h3>
                    <span class="px-4 py-2 bg-stone-100 dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-full text-[9px] font-black uppercase tracking-widest text-stone-500 italic">Discount Power Authorized</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($pwrTiers as $tier)
                        <div class="bg-white dark:bg-stone-900 rounded-[2.5rem] p-8 border border-stone-100 dark:border-stone-800 shadow-xl flex flex-col items-center text-center transition-all hover:scale-[1.03] group {{ $points < $tier['pts'] ? 'opacity-50 grayscale' : '' }}">
                            <div class="w-16 h-16 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500 mb-6 group-hover:bg-amber-500 group-hover:text-stone-950 transition-colors">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h4 class="font-black text-stone-900 dark:text-white uppercase italic text-sm mb-1 text-center">{{ $tier['label'] }}</h4>
                            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-6 text-center">{{ number_format($tier['pts']) }} PTS</p>
                            
                            @if($points >= $tier['pts'])
                                <form action="{{ route('rewards.claim') }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="reward_id" value="{{ $tier['id'] }}">
                                    <button type="submit" class="w-full py-3 bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-900 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg hover:bg-amber-600 dark:hover:bg-amber-500 transition-colors active:scale-95">
                                        Activate Power
                                    </button>
                                </form>
                            @else
                                <div class="w-full py-3 bg-stone-100 dark:bg-stone-800 text-stone-400 rounded-xl text-[9px] font-black uppercase tracking-widest cursor-not-allowed italic text-center">
                                    {{ number_format($tier['pts'] - $points) }} More Required
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 🟢 DIGITAL FREQUENCY PROTOCOL: Milestone Card --}}
            <div class="bg-white dark:bg-stone-900 rounded-[2.5rem] p-8 border border-stone-100 dark:border-stone-800 shadow-xl mb-16">
                <div class="flex flex-col md:flex-row justify-between items-center gap-8 text-left">
                    <div class="text-center md:text-left">
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-600 mb-2 italic">Loyalty Loop</p>
                        <h4 class="text-2xl font-black text-stone-900 dark:text-white uppercase italic tracking-tighter mb-2">Digital Punch Card</h4>
                        <p class="text-[11px] font-bold text-stone-500 dark:text-stone-400 uppercase leading-relaxed italic max-w-lg">
                            Every <span class="text-stone-900 dark:text-white font-black">10th completed order commit</span> authorizes an automatic <span class="text-amber-600 font-black">+150 Star Points</span> bonus to your asset ledger.
                        </p>
                    </div>
                    <div class="px-8 py-5 bg-stone-50 dark:bg-stone-950 border border-stone-200 dark:border-stone-800 rounded-[2rem] shadow-inner text-center">
                        <p class="text-[8px] font-black text-stone-400 uppercase tracking-widest mb-1">Active Reward</p>
                        <p class="text-xl font-bold text-amber-600 tracking-tight">150 PTS / 10 ORDERS</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>