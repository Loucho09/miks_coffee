<x-app-layout>
    @php
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // 🟢 FIX: Reference loyalty_points for the total balance
        $points = $user->loyalty_points ?? 0; 
        
        $target = 100;
        if ($points >= 100) $target = 200;
        if ($points >= 200) $target = 500;
        $progress = min(($points / $target) * 100, 100);
        
        $history = \App\Models\PointTransaction::where('user_id', Auth::id())->latest()->get();

        $tier = $user->loyalty_tier; // Uses the model's tier logic

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
            
            <div class="bg-stone-100 dark:bg-stone-900 rounded-[3rem] p-8 md:p-16 text-center text-white mb-12 shadow-2xl relative overflow-hidden border border-stone-800">
                <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full blur-[100px] -mr-32 -mt-32"></div>
                <div class="mb-10 inline-flex items-center gap-3 px-6 py-2.5 rounded-full bg-amber-500/10 border border-amber-500/20">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-500">{{ $tier }} Tier Status</span>
                </div>
                <p class="text-stone-500 text-sm uppercase tracking-[0.5em] font-black mb-6 italic">Star Points Balance</p>
                <h1 class="text-9xl md:text-[14rem] font-black text-amber-500 mb-12 drop-shadow-[0_10px_30px_rgba(245,158,11,0.3)] italic tracking-tighter leading-none">{{ $points }}</h1>
                
                <div class="max-w-xl mx-auto mt-12">
                    <div class="flex justify-between text-[9px] font-black text-stone-500 mb-5 uppercase tracking-[0.3em] px-2">
                        <span>Current: {{ $points }}</span>
                        <span class="text-stone-500">Goal: {{ $target }} PTS</span>
                    </div>
                    <div class="w-full bg-stone-950 rounded-full h-3 overflow-hidden border border-stone-800 p-0.5 shadow-inner">
                        <div class="bg-gradient-to-r from-amber-600 to-amber-400 h-full rounded-full transition-all duration-[2.5s] ease-out shadow-[0_0_15px_rgba(217,119,6,0.5)]" 
                             :style="`width: ${currentProgress}%`" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>

            <div class="mb-16">
                <div class="flex items-center justify-between px-2 mb-8">
                    <h3 class="font-black text-stone-900 dark:text-white uppercase tracking-tighter italic text-2xl">Redemption Vault</h3>
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
        </div>
    </div>
</x-app-layout>