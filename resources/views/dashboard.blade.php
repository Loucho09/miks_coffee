<x-app-layout>
    @php
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $claimablePoints = 0;
        $unreviewedItems = [];
        
        if(isset($recentOrders)) {
            foreach($recentOrders as $order) {
                $status = strtolower($order->status);
                if(in_array($status, ['completed', 'ready'])) {
                    foreach($order->items as $item) {
                        if(!$item->review) {
                            $claimablePoints += 2;
                            $unreviewedItems[] = $item;
                        }
                    }
                }
            }
        }

        $dashPoints = $user->loyalty_points ?? 0;
        $dashTier = $dashPoints >= 500 ? 'Gold' : ($dashPoints >= 200 ? 'Silver' : 'Bronze');
        $perc = min(($dashPoints % 100), 100);
        
        $firstToReview = $unreviewedItems[0] ?? null;
        $transactions = $user->pointTransactions()->latest()->take(10)->get();

        $streakCount = $user->streak_count ?? 0;
        $nextMilestone = (floor($streakCount / 3) + 1) * 3;
        $streakProgress = (($streakCount % 3) / 3) * 100;
    @endphp

    <style>
        :root {
            --fluid-32-64: clamp(1.5rem, 4vw + 0.5rem, 3.5rem);
            --fluid-18-32: clamp(1rem, 2vw + 0.5rem, 1.75rem);
        }
        [x-cloak] { display: none !important; }
        .shadow-premium { box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.05); }
        .dark .shadow-premium { box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5); }
        .glass-card { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div x-data="{ reviewModal: false, selectedProduct: '', selectedItemId: '' }" class="py-4 sm:py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-all duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-4 sm:mb-8 flex items-center p-3 sm:p-5 text-stone-800 dark:text-stone-200 border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 rounded-xl sm:rounded-[2rem] shadow-premium transition-all">
                    <div class="flex-shrink-0 mr-3 text-emerald-500 bg-emerald-500/10 p-1.5 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="text-[9px] sm:text-xs font-black uppercase tracking-[0.1em] leading-none">{{ session('success') }}</div>
                </div>
            @endif

            <div class="mb-6 sm:mb-16 flex flex-col md:flex-row md:items-end justify-between gap-4 sm:gap-8">
                <div class="min-w-0">
                    <span class="text-amber-600 font-black uppercase tracking-[0.4em] text-[8px] sm:text-[10px] mb-1 sm:mb-4 block">Session active</span>
                    <h2 class="font-black text-stone-900 dark:text-white uppercase italic leading-[1.1] tracking-tighter transition-colors duration-500" style="font-size: var(--fluid-32-64)">
                        Good {{ date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') }}, <br class="hidden sm:block"> {{ $user->name }}
                    </h2>
                </div>
                <div class="flex items-center gap-3 sm:gap-5 bg-white dark:bg-stone-900 px-5 py-3 sm:px-8 sm:py-5 rounded-2xl sm:rounded-[2.5rem] border border-stone-200 dark:border-stone-800 shadow-premium shrink-0 self-start group transition-all">
                    <svg class="w-5 h-5 sm:w-8 sm:h-8 {{ $streakCount > 0 ? 'text-amber-500' : 'text-stone-300 dark:text-stone-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-1.513-1.155-2.452a10.11 10.11 0 00-.4-1.045zM10 17a1 1 0 100-2 1 1 0 000 2z" /></svg>
                    <div class="flex flex-col">
                        <span class="text-xs sm:text-[14px] font-black text-stone-900 dark:text-white leading-none tracking-tight uppercase">{{ $streakCount }} Day Streak</span>
                        <span class="text-[7px] sm:text-[9px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-widest mt-0.5 sm:mt-1">Active sequence</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-8 items-start">
                <div class="lg:col-span-4 space-y-4 sm:space-y-8">
                    {{-- Loyalty Card --}}
                    <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-900 rounded-3xl sm:rounded-[3rem] p-6 sm:p-10 text-white shadow-premium border border-stone-800 transition-all">
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-6 sm:mb-12">
                                <span class="px-3 py-1 bg-amber-600/10 border border-amber-500/20 rounded-full text-[8px] sm:text-[9px] font-black uppercase tracking-widest text-amber-500 italic">{{ $dashTier }} tier</span>
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white/10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                            </div>
                            <p class="text-[8px] sm:text-[10px] uppercase tracking-[0.4em] text-stone-500 font-black mb-2 sm:mb-4 leading-none">Net loyalty assets</p>
                            <div class="flex items-baseline gap-2 sm:gap-3 mb-6 sm:mb-8">
                                <span class="text-5xl sm:text-7xl font-black dark:text-stone-400 tracking-tighter leading-none">{{ $dashPoints }}</span>
                                <span class="text-stone-900 dark:text-white font-black text-sm sm:text-xl tracking-widest uppercase italic">PTS</span>
                            </div>
                            <div class="w-full bg-stone-800 rounded-full h-2 sm:h-3 overflow-hidden shadow-inner border border-white/5">
                                <div class="bg-gradient-to-r from-amber-600 to-amber-400 h-full rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(217,119,6,0.3)]" :style="'width: ' + {{ $perc }} + '%'"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-stone-900 rounded-3xl sm:rounded-[2.5rem] p-5 sm:p-8 border border-stone-200 dark:border-stone-800 shadow-premium">
                        <div class="flex justify-between items-center mb-4 sm:mb-8">
                            <h4 class="text-stone-900 dark:text-white font-black text-[8px] sm:text-[10px] uppercase tracking-[0.4em] italic leading-none">Sequence</h4>
                            <span class="text-amber-500 font-black text-[10px] tracking-tighter italic">{{ $streakCount }}/{{ $nextMilestone }}</span>
                        </div>
                        <div class="relative h-4 sm:h-6 w-full bg-stone-50 dark:bg-stone-950 rounded-xl sm:rounded-2xl overflow-hidden border border-stone-100 dark:border-stone-800 mb-4 shadow-inner p-1">
                            <div class="absolute top-1 bottom-1 left-1 bg-gradient-to-r from-amber-600 to-amber-500 rounded-lg transition-all duration-1000" :style="'width: ' + {{ $streakProgress }} + '%'"></div>
                        </div>
                        <p class="text-stone-500 dark:text-stone-400 text-[8px] sm:text-[10px] font-bold uppercase tracking-tight leading-relaxed italic text-center">
                            Authorize <span class="text-stone-900 dark:text-stone-200">{{ $nextMilestone - $streakCount }} units</span> for <span class="text-amber-600 font-black">+20 bonus</span>.
                        </p>
                    </div>

                    @if($claimablePoints > 0)
                        <div class="bg-amber-600 rounded-3xl sm:rounded-[3rem] p-6 sm:p-10 relative overflow-hidden shadow-xl group cursor-pointer" @click="reviewModal = true; selectedProduct = '{{ addslashes($firstToReview->product->name) }}'; selectedItemId = '{{ $firstToReview->id }}'">
                            <div class="relative z-10 text-stone-950">
                                <div class="flex items-center gap-3 mb-4 sm:mb-6">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-stone-950/10 border border-stone-950/10 flex items-center justify-center animate-bounce">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <span class="px-2 py-1 bg-stone-950 text-white rounded-md text-[7px] sm:text-[8px] font-black uppercase tracking-widest">Pending yield</span>
                                </div>
                                <h4 class="font-black text-xl sm:text-3xl uppercase tracking-tighter mb-2 italic leading-tight">+{{ $claimablePoints }} PTS AVAILABLE</h4>
                                <div class="w-full flex items-center justify-center py-3 sm:py-5 bg-stone-950 text-white rounded-xl sm:rounded-[2rem] text-[8px] sm:text-[10px] font-black uppercase tracking-widest shadow-2xl transition-all">
                                    Audit terminal
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white dark:bg-stone-900 rounded-3xl sm:rounded-[2.5rem] p-5 sm:p-8 border border-stone-200 dark:border-stone-800 shadow-premium">
                        <h4 class="text-amber-600 font-black text-[8px] sm:text-[10px] uppercase tracking-[0.4em] mb-4 italic leading-none">Peer induction protocol</h4>
                        <p class="text-stone-500 dark:text-stone-400 text-[10px] mb-8 leading-relaxed font-bold italic uppercase">Induct new members. A dual <span class="text-stone-900 dark:text-white underline">50 PTS yield</span> is authorized upon their first order commit.</p>
                        <div class="relative flex items-center gap-2">
                            <input type="text" readonly id="refLink" value="{{ route('register', ['ref' => $user->referral_code]) }}" 
                                class="w-full bg-stone-50 dark:bg-stone-950 border-stone-100 dark:border-stone-800 text-stone-400 rounded-xl py-3 pl-4 pr-16 text-[8px] font-black uppercase transition-all shadow-inner">
                            <button onclick="copyReferralLink()" class="absolute right-1.5 bg-stone-900 dark:bg-stone-100 text-white dark:text-stone-900 font-black px-3 py-1.5 rounded-lg text-[7px] uppercase tracking-widest shadow-md">Copy</button>
                        </div>
                    </div>

                    <div class="bg-stone-400 dark:bg-stone-900 rounded-3xl sm:rounded-[2.5rem] p-6 sm:p-10 border border-stone-800 shadow-premium transition-all">
                        <span class="text-[8px] sm:text-[10px] font-black text-amber-600 uppercase tracking-[0.4em] block mb-4 italic">System core</span>
                        <h4 class="text-lg sm:text-2xl font-black text-white uppercase italic tracking-tighter mb-6 leading-tight">Data Management</h4>
                        <div class="flex flex-col gap-3">
                            <a href="{{ route('profile.data_report') }}" class="w-full py-4 bg-stone-50 text-stone-950 rounded-xl sm:rounded-2xl font-black uppercase tracking-widest text-[8px] sm:text-[10px] transition-all flex items-center justify-center gap-3">
                                Generate Audit
                            </a>
                            <form action="{{ route('profile.export') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full py-3 text-stone-500 rounded-xl font-black uppercase tracking-widest text-[7px] border border-stone-800 transition-all flex items-center justify-center">
                                    Export JSON
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-6 sm:space-y-12">
                    <div class="flex items-center justify-between px-1">
                        <h3 class="font-black text-stone-900 dark:text-white uppercase tracking-tighter italic transition-colors" style="font-size: var(--fluid-18-32)">Live manifest</h3>
                        <a href="{{ route('orders.index') }}" class="text-[8px] sm:text-[9px] font-black uppercase tracking-[0.2em] text-stone-400 dark:text-stone-500 hover:text-amber-600 transition-colors">History ↗</a>
                    </div>

                    <div class="space-y-4 sm:space-y-8">
                        @forelse($recentOrders as $order)
                            <div class="bg-white dark:bg-stone-900 rounded-[2rem] sm:rounded-[3rem] p-5 sm:p-12 border border-stone-200 dark:border-stone-800 shadow-premium group transition-all duration-500">
                                <div class="flex flex-col md:flex-row justify-between md:items-start border-b border-stone-100 dark:border-stone-800 pb-5 sm:pb-10 mb-5 sm:mb-10 gap-4 sm:gap-8">
                                    <div class="flex items-center gap-4 sm:gap-8">
                                        <div class="w-12 h-12 sm:w-20 sm:h-20 rounded-xl sm:rounded-[2rem] bg-stone-50 dark:bg-stone-950 border border-stone-100 dark:border-stone-800 flex items-center justify-center shrink-0 shadow-inner overflow-hidden">
                                            @if(strtolower($order->status) == 'preparing')
                                                <svg class="w-6 h-6 sm:w-10 sm:h-10 animate-pulse text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            @else
                                                <svg class="w-6 h-6 sm:w-10 sm:h-10 text-stone-900 dark:text-stone-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2 sm:gap-4 mb-1 sm:mb-4">
                                                <p class="font-black text-lg sm:text-4xl text-stone-900 dark:text-white tracking-tighter uppercase italic leading-none transition-colors">Unit #{{ $order->id }}</p>
                                                <a href="{{ route('orders.receipt', $order->id) }}" class="text-[7px] sm:text-[9px] bg-stone-900 dark:bg-stone-100 text-white dark:text-stone-900 font-black px-2 py-1 rounded-md sm:rounded-xl uppercase tracking-widest shadow-lg">Receipt</a>
                                            </div>
                                            <p class="text-[8px] sm:text-[11px] font-black uppercase text-stone-400 dark:text-stone-500 italic tracking-[0.1em] flex items-center gap-1">
                                                {{ $order->created_at->format('M d • h:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex flex-row md:flex-col justify-between items-center md:items-end w-full md:w-auto pt-4 md:pt-0 border-t md:border-none border-stone-100 dark:border-stone-800">
                                        <p class="text-xl sm:text-4xl font-black text-stone-900 dark:text-white tracking-tighter leading-none italic transition-colors">₱{{ number_format($order->total_price, 2) }}</p>
                                        <div class="mt-2 px-3 py-1.5 sm:px-5 sm:py-2.5 bg-stone-50 dark:bg-stone-950 border border-stone-200 dark:border-stone-800 rounded-full text-[8px] sm:text-[10px] font-black uppercase tracking-[0.2em] text-amber-600 italic leading-none transition-all shadow-inner">
                                            {{ $order->status }}
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-6">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center justify-between p-4 sm:p-6 rounded-2xl sm:rounded-[2.5rem] bg-stone-50/50 dark:bg-stone-950/30 border border-stone-100 dark:border-stone-800/50 transition-all">
                                            <div class="flex items-center gap-3 sm:gap-5 min-w-0">
                                                <div class="w-10 h-10 sm:w-16 sm:h-16 rounded-lg sm:rounded-2xl overflow-hidden border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 shrink-0">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-stone-100 dark:bg-stone-800">
                                                            <svg class="w-4 h-4 text-stone-300 dark:text-stone-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.703 2.703 0 00-3 0 2.704 2.704 0 01-3 0 2.703 2.703 0 00-3 0 2.704 2.704 0 01-1.5-.454M3 20h18M4 11a4 4 0 118 0v1H4v-1z"/></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="min-w-0 truncate">
                                                    <p class="font-black text-xs sm:text-base text-stone-900 dark:text-white uppercase truncate transition-colors">{{ $item->product->name ?? 'Brew unit' }}</p>
                                                    <p class="text-[7px] sm:text-[9px] font-black text-stone-400 dark:text-stone-500 uppercase italic mt-0.5">{{ $item->quantity }}x • {{ $item->size }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 ml-2 shrink-0">
                                                @if(!$item->review && in_array(strtolower($order->status), ['completed', 'ready']))
                                                    <button @click="reviewModal = true; selectedProduct = '{{ addslashes($item->product->name) }}'; selectedItemId = '{{ $item->id }}'" class="px-4 py-2 bg-stone-900 dark:bg-stone-100 text-white dark:text-stone-900 rounded-lg sm:rounded-[1.25rem] text-[7px] sm:text-[9px] font-black uppercase tracking-widest shadow-xl transition-all active:scale-90">Review</button>
                                                @elseif($item->review)
                                                    <div class="flex items-center gap-0.5 px-2 py-1 bg-white dark:bg-stone-800 rounded-full border border-stone-200 dark:border-stone-700">
                                                        @foreach(range(1, 5) as $star)<svg class="w-2 h-2 sm:w-3.5 sm:h-3.5 {{ $star <= $item->review->rating ? 'text-amber-500' : 'text-stone-200 dark:text-stone-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="py-20 sm:py-40 text-center opacity-30 italic font-black uppercase text-stone-500 tracking-[0.4em] text-[9px] sm:text-[11px] border-2 border-dashed border-stone-200 dark:border-stone-800 rounded-[2rem] sm:rounded-[3.5rem]">No logs available</div>
                        @endforelse
                    </div>

                    <div class="mt-12 sm:mt-24">
                        <div class="flex items-center justify-between px-1 mb-6 sm:mb-10">
                            <h3 class="font-black text-stone-900 dark:text-white uppercase tracking-tighter italic leading-none transition-colors" style="font-size: var(--fluid-18-32)">Activity Feed</h3>
                            <span class="text-[8px] sm:text-[10px] font-black uppercase tracking-[0.4em] text-stone-400 dark:text-stone-500">Asset log</span>
                        </div>
                        <div class="bg-white dark:bg-stone-900 rounded-3xl sm:rounded-[3rem] border border-stone-200 dark:border-stone-800 shadow-premium overflow-hidden">
                            <div class="overflow-x-auto no-scrollbar">
                                <table class="w-full text-left border-collapse min-w-[300px]">
                                    <thead>
                                        <tr class="border-b border-stone-100 dark:border-stone-800 bg-stone-50 dark:bg-stone-950/40 transition-colors">
                                            <th class="px-6 py-5 sm:px-12 sm:py-8 text-[8px] sm:text-[11px] font-black uppercase tracking-[0.4em] text-stone-400 dark:text-stone-500 italic">Identifier</th>
                                            <th class="px-6 py-5 sm:px-12 sm:py-8 text-[8px] sm:text-[11px] font-black uppercase tracking-[0.4em] text-stone-400 dark:text-stone-500 text-right italic">Yield</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                                        @forelse($transactions as $tx)
                                            <tr class="group hover:bg-stone-50 dark:hover:bg-stone-950/60 transition-all">
                                                <td class="px-6 py-5 sm:px-12 sm:py-8">
                                                    <p class="font-black text-stone-900 dark:text-white text-[10px] sm:text-base uppercase tracking-tight transition-colors">{{ $tx->description }}</p>
                                                    <p class="text-[7px] sm:text-[11px] font-black text-stone-400 dark:text-stone-500 uppercase italic tracking-widest mt-1">{{ $tx->created_at->format('M d, Y') }}</p>
                                                </td>
                                                <td class="px-6 py-5 sm:px-12 sm:py-8 text-right">
                                                    <span class="font-black italic text-sm sm:text-xl {{ $tx->amount > 0 ? 'text-emerald-600 dark:text-emerald-500' : 'text-rose-600 dark:text-rose-500' }}">
                                                        {{ $tx->amount > 0 ? '+' : '' }}{{ $tx->amount }} <span class="text-[7px] sm:text-[10px] uppercase tracking-[0.2em] ml-1 opacity-60">PTS</span>
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="2" class="px-6 py-12 text-center opacity-30 italic font-black uppercase text-stone-500 tracking-[0.4em] text-[9px]">Ledger empty</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Concierge Threads Section --}}
                    <div class="mt-16 sm:mt-28 space-y-8 sm:space-y-16 pb-12 sm:pb-24">
                        <div class="flex items-center justify-between px-1">
                            <h3 class="font-black text-stone-900 dark:text-white uppercase tracking-tighter italic leading-none transition-colors" style="font-size: var(--fluid-18-32)">Concierge threads</h3>
                        </div>
                        @if(isset($supportTickets))
                            <div class="space-y-6 sm:space-y-10">
                                @forelse($supportTickets as $ticket)
                                    <div class="bg-white dark:bg-stone-900 rounded-[2rem] sm:rounded-[3.5rem] p-6 sm:p-14 border border-stone-200 dark:border-stone-800 shadow-premium transition-all">
                                        <div class="flex flex-wrap items-center gap-3 sm:gap-5 mb-6 sm:mb-10">
                                            <span class="px-3 py-1.5 bg-stone-100 dark:bg-stone-950 border border-stone-200 dark:border-stone-800 rounded-lg text-[7px] sm:text-[10px] font-black uppercase text-stone-500 tracking-widest transition-colors shadow-inner">ID #{{ $ticket->id }}</span>
                                            <div class="px-3 py-1.5 rounded-lg text-[7px] sm:text-[10px] font-black uppercase tracking-[0.2em] italic {{ $ticket->status == 'pending' ? 'bg-amber-500/10 text-amber-600 animate-pulse' : ($ticket->status == 'replied' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-stone-100 dark:bg-stone-950 text-stone-500') }} shadow-sm">{{ strtoupper($ticket->status) }}</div>
                                        </div>
                                        <h4 class="text-lg sm:text-3xl font-black text-stone-900 dark:text-white uppercase italic mb-4 transition-colors leading-tight tracking-tighter">{{ $ticket->subject }}</h4>
                                        <div class="p-6 sm:p-10 bg-stone-50 dark:bg-stone-950 border border-stone-100 dark:border-stone-800 rounded-[1.5rem] sm:rounded-[3rem] relative mb-6 sm:mb-12 transition-colors shadow-inner">
                                            <p class="text-xs sm:text-base text-stone-600 dark:text-stone-400 italic leading-relaxed font-medium">"{{ $ticket->message }}"</p>
                                        </div>

                                        {{-- 🟢 FEATURE: Correct Admin Reply Rendering --}}
                                        @if($ticket->replies && $ticket->replies->count() > 0)
                                            <div class="space-y-6 sm:space-y-12 border-t border-stone-100 dark:border-stone-800 pt-6 sm:pt-14 transition-colors">
                                                @foreach($ticket->replies as $reply)
                                                    <div class="flex gap-4 sm:gap-8 items-start group">
                                                        <div class="shrink-0 w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-[1.25rem] bg-stone-900 dark:bg-stone-100 flex items-center justify-center text-white dark:text-stone-900 shadow-xl transition-all">
                                                            <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between mb-2 sm:mb-4">
                                                                <span class="text-[8px] sm:text-[11px] font-black uppercase text-amber-600 tracking-[0.3em] italic leading-none transition-colors">Response active</span>
                                                                <span class="text-[7px] sm:text-[9px] text-stone-400 dark:text-stone-500 font-black uppercase transition-colors tracking-widest">{{ $reply->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <div class="bg-stone-100 dark:bg-stone-950/60 p-5 sm:p-10 rounded-2xl sm:rounded-[3rem] border border-stone-200 dark:border-stone-800 transition-all shadow-premium group-hover:shadow-lg">
                                                                <p class="text-xs sm:text-base text-stone-700 dark:text-stone-300 italic leading-relaxed font-medium transition-colors">{{ $reply->message }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="py-16 text-center opacity-30 italic font-black uppercase text-stone-500 tracking-[0.4em] text-[8px] sm:text-[11px]">System log void</div>
                                @endforelse
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Review Modal --}}
        <div x-show="reviewModal" class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-2 sm:p-4 bg-stone-950/95 backdrop-blur-2xl" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="bg-white dark:bg-stone-900 w-full max-w-xl rounded-[2.5rem] sm:rounded-[4.5rem] border border-stone-200 dark:border-stone-800 shadow-connected overflow-hidden relative transition-all duration-500" @click.away="reviewModal = false">
                <div class="absolute top-0 right-0 p-6 sm:p-10">
                    <button @click="reviewModal = false" class="p-2 sm:p-4 text-stone-400 hover:text-stone-900 dark:hover:text-white bg-stone-50 dark:bg-stone-950 rounded-xl sm:rounded-2xl border border-stone-100 dark:border-stone-800 transition-all active:scale-90 group">
                        <svg class="h-4 w-4 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-8 sm:p-20">
                    <div class="mb-8 sm:mb-14">
                        <div class="w-12 sm:w-16 h-1 bg-amber-600 rounded-full mb-6 sm:mb-8"></div>
                        <span class="text-amber-600 text-[8px] sm:text-[11px] font-black uppercase tracking-[0.5em] mb-3 sm:mb-4 block italic leading-none transition-colors">Quality Assurance</span>
                        <h3 class="text-stone-900 dark:text-white font-black text-2xl sm:text-5xl uppercase tracking-tighter italic leading-[0.9] transition-colors duration-500">Unit Audit</h3>
                        <p class="text-stone-400 dark:text-stone-500 text-[8px] sm:text-[11px] font-black uppercase mt-4 sm:mt-8 tracking-[0.2em] flex items-center gap-3 transition-colors" x-text="'Target ID: ' + selectedProduct"></p>
                    </div>
                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-8 sm:space-y-14">
                        @csrf
                        <input type="hidden" name="order_item_id" :value="selectedItemId">
                        <div>
                            <label class="block text-[8px] sm:text-[11px] font-black uppercase tracking-[0.4em] text-stone-500 mb-6 sm:mb-12 text-center italic transition-colors">Intensity ranking</label>
                            <div class="flex flex-wrap justify-center gap-2 sm:gap-5">
                                @foreach(range(1, 5) as $rating)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="rating" value="{{ $rating }}" class="hidden peer" required>
                                        <div class="w-10 h-10 sm:w-16 sm:h-16 rounded-xl sm:rounded-[1.75rem] border-2 border-stone-100 dark:border-stone-800 flex items-center justify-center peer-checked:bg-stone-900 dark:peer-checked:bg-amber-600 peer-checked:border-stone-900 dark:peer-checked:border-amber-600 peer-checked:text-white dark:peer-checked:text-stone-950 text-stone-300 dark:text-stone-700 font-black transition-all group-hover:border-amber-500 shadow-sm text-sm sm:text-3xl italic">{{ $rating }}</div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-[8px] sm:text-[11px] font-black uppercase tracking-[0.4em] text-stone-500 mb-3 sm:mb-6 px-4 italic leading-none transition-colors">Sensory data entry</label>
                            <textarea name="comment" rows="3" class="w-full bg-stone-50 dark:bg-stone-950 border-stone-200 dark:border-stone-800 border-2 rounded-[1.5rem] sm:rounded-[3rem] px-6 py-6 sm:px-10 sm:py-10 text-stone-900 dark:text-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all text-xs sm:text-lg italic shadow-inner outline-none resize-none" placeholder="Transmit sensory profile..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-5 sm:py-8 bg-stone-900 dark:bg-stone-100 text-white dark:text-stone-900 rounded-2xl sm:rounded-[3rem] font-black uppercase tracking-[0.3em] shadow-2xl transition-all hover:bg-amber-600 active:scale-[0.98] text-[7px] sm:text-[10px]">Authenticate protocol & yield +2 PTS</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyReferralLink() {
            const copyText = document.getElementById("refLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            const btn = event.target;
            const originalText = btn.innerText;
            btn.innerText = "COPIED";
            btn.classList.add('!bg-emerald-600', '!text-white');
            
            setTimeout(() => {
                btn.innerText = originalText;
                btn.classList.remove('!bg-emerald-600', '!text-white');
            }, 3000);
        }
    </script>
</x-app-layout>