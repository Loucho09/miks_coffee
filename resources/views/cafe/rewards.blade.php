<x-app-layout>
    @php
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // 🟢 FIXED: Reference standardized loyalty_points for 68 PTS display
        $points = $user->loyalty_points ?? 0; 
        
        $target = 100; 
        $progress = min(($points / $target) * 100, 100);
        $remaining = max(0, $target - $points);
        
        $history = \App\Models\PointTransaction::where('user_id', Auth::id())
                    ->latest()
                    ->get();

        $tier = 'Bronze';
        if($points >= 500) $tier = 'Gold';
        elseif($points >= 200) $tier = 'Silver';
    @endphp

    <div class="py-12" x-data="{ progress: {{ $progress }} }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-stone-900 rounded-3xl p-8 md:p-12 text-center text-white mb-12 shadow-connected relative overflow-hidden border border-stone-800">
                <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                    <div class="absolute right-0 top-0 transform translate-x-1/3 -translate-y-1/3 text-[20rem]">☕</div>
                </div>

                <div class="mb-6 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 border border-amber-500/20">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-amber-600">{{ $tier }} Tier Status</span>
                </div>

                <p class="text-stone-400 text-lg uppercase tracking-[0.4em] font-black mb-4 italic">Star Points Balance</p>
                <h1 class="text-8xl md:text-[12rem] font-black text-amber-500 mb-8 drop-shadow-2xl italic tracking-tighter">{{ $points }}</h1>
                
                <div class="max-w-xl mx-auto mt-10">
                    <div class="flex justify-between text-[10px] font-black text-stone-500 mb-4 uppercase tracking-[0.3em]"><span>0 Balance</span><span>Goal: {{ $target }}</span></div>
                    <div class="w-full bg-stone-800 rounded-full h-2 overflow-hidden border border-stone-700 shadow-inner">
                        <div class="bg-amber-600 h-full rounded-full transition-all duration-[2s] ease-out shadow-[0_0_15px_rgba(217,119,6,0.4)]" :style="`width: ${progress}%`" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="bg-white dark:bg-stone-900 p-8 rounded-[2rem] border border-stone-100 dark:border-stone-800 shadow-sm transition-all hover:scale-105">
                    <p class="text-[10px] font-black uppercase tracking-widest text-stone-500 mb-4">Milestones</p>
                    <p class="text-3xl font-black text-stone-900 dark:text-white">{{ $history->where('amount', '>', 0)->sum('amount') }} <span class="text-xs text-stone-400 italic">PTS</span></p>
                </div>
                <div class="bg-white dark:bg-stone-900 p-8 rounded-[2rem] border border-stone-100 dark:border-stone-800 shadow-sm transition-all hover:scale-105">
                    <p class="text-[10px] font-black uppercase tracking-widest text-stone-500 mb-4">Used</p>
                    <p class="text-3xl font-black text-stone-900 dark:text-white">{{ abs($history->where('amount', '<', 0)->sum('amount')) }} <span class="text-xs text-stone-400 italic">PTS</span></p>
                </div>
                <div class="bg-stone-900 p-8 rounded-[2rem] border border-stone-800 shadow-connected">
                    <p class="text-[10px] font-black uppercase tracking-widest text-stone-500 mb-4">Latest</p>
                    <p class="text-xs font-bold text-amber-500 uppercase italic truncate">{{ $history->first()->description ?? 'No Activity' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>