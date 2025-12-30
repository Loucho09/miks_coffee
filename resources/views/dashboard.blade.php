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

        $dashPoints = $user->points ?? 0;
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
            --fluid-32-64: clamp(2rem, 5vw + 1rem, 4rem);
            --fluid-18-32: clamp(1.125rem, 3vw + 0.5rem, 2rem);
        }
        [x-cloak] { display: none !important; }
        .shadow-connected { box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.5); }
    </style>

    <div x-data="{ reviewModal: false, selectedProduct: '', selectedItemId: '' }" class="py-6 sm:py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-8 flex items-center p-4 text-stone-200 border border-stone-800 bg-stone-900 rounded-2xl shadow-sm transition-all">
                    <div class="flex-shrink-0 mr-3 text-amber-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="text-[10px] sm:text-xs font-black uppercase tracking-widest leading-none">{{ session('success') }}</div>
                </div>
            @endif

            <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="min-w-0">
                    <span class="text-stone-500 font-black uppercase tracking-[0.4em] text-[10px] mb-2 block">Command Center</span>
                    <h2 class="font-black text-stone-900 dark:text-white uppercase italic leading-[1.1] tracking-tighter break-words" style="font-size: var(--fluid-32-64)">
                        Good {{ date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') }}, <br> {{ $user->name }}
                    </h2>
                </div>
                <div class="flex items-center gap-4 bg-stone-900 px-6 py-4 rounded-3xl border border-stone-800 shadow-xl shrink-0 self-start">
                    <svg class="w-6 h-6 {{ $user->streak_count > 1 ? 'text-amber-500 animate-pulse' : 'text-stone-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-1.513-1.155-2.452a10.11 10.11 0 00-.4-1.045zM10 17a1 1 0 100-2 1 1 0 000 2z" /></svg>
                    <span class="text-[10px] sm:text-xs font-black uppercase tracking-[0.2em] text-white">{{ $user->streak_count ?? 1 }} Day Streak</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-4 space-y-8">
                    <div class="relative overflow-hidden bg-stone-900 rounded-[3rem] p-10 text-white shadow-connected border border-stone-800">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-stone-800/40 rounded-full blur-[80px] -mr-20 -mt-20"></div>
                        <div class="relative z-10">
                            <p class="text-[10px] uppercase tracking-[0.4em] text-stone-500 font-black mb-10 italic">{{ $dashTier }} Member</p>
                            <p class="text-[10px] uppercase tracking-[0.4em] text-stone-500 font-black mb-3">Loyalty Points</p>
                            <div class="flex items-baseline gap-3 mb-6">
                                <span class="text-6xl sm:text-7xl font-black text-white tracking-tighter leading-none">{{ $dashPoints }}</span>
                                <span class="text-stone-500 font-black text-xl tracking-widest uppercase">PTS</span>
                            </div>
                            <div class="w-full bg-stone-800 rounded-full h-2 overflow-hidden shadow-inner">
                                <div class="bg-stone-400 h-full rounded-full transition-all duration-1000" :style="'width: ' + {{ $perc }} + '%'"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-stone-900 rounded-[2.5rem] p-8 border border-stone-800 shadow-connected relative overflow-hidden group">
                        <div class="relative z-10">
                            <div class="flex justify-between items-center mb-6">
                                <h4 class="text-white font-black text-[10px] uppercase tracking-[0.4em] italic">Streak Progress</h4>
                                <span class="text-amber-500 font-black text-[10px] uppercase tracking-widest">{{ $streakCount }}/{{ $nextMilestone }} Days</span>
                            </div>
                            <div class="relative h-4 w-full bg-stone-950 rounded-full overflow-hidden border border-stone-800 mb-4 shadow-inner">
                                <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-amber-600 to-amber-400 rounded-full transition-all duration-1000" :style="'width: ' + {{ $streakProgress }} + '%'"></div>
                            </div>
                            <p class="text-stone-500 text-[9px] font-bold uppercase tracking-tight leading-relaxed italic">
                                Order <span class="text-stone-300">{{ $nextMilestone - $streakCount }} more consecutive days</span> to claim a <span class="text-amber-500">+20 PTS Bonus</span>.
                            </p>
                        </div>
                    </div>

                    @if($claimablePoints > 0)
                        <div class="bg-amber-600 rounded-[3rem] p-8 relative overflow-hidden shadow-xl group">
                            <div class="relative z-10 text-stone-950">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="w-12 h-12 rounded-2xl bg-stone-950/10 flex items-center justify-center">
                                        <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    </div>
                                    <span class="px-3 py-1 bg-stone-950 text-white rounded-full text-[8px] font-black uppercase tracking-widest whitespace-nowrap">Rewards waiting</span>
                                </div>
                                <h4 class="font-black text-2xl uppercase tracking-tighter mb-2 italic leading-tight">+{{ $claimablePoints }} PTS AVAILABLE</h4>
                                <p class="text-[10px] mb-8 font-bold uppercase tracking-tight opacity-80 leading-relaxed">You have {{ count($unreviewedItems) }} unrated brews. Start claiming now.</p>
                                <button @click="reviewModal = true; selectedProduct = '{{ addslashes($firstToReview->product->name) }}'; selectedItemId = '{{ $firstToReview->id }}'" 
                                        class="w-full inline-flex items-center justify-center text-[10px] font-black uppercase tracking-[0.2em] px-6 py-4 bg-stone-950 text-white rounded-2xl hover:scale-[1.02] transition-all shadow-2xl">
                                    Claim Points Now
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="bg-stone-900 rounded-[2.5rem] p-8 border border-stone-800 shadow-connected relative overflow-hidden group">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-amber-500/5 rounded-full blur-2xl"></div>
                        <div class="relative z-10">
                            <h4 class="text-amber-500 font-black text-[10px] uppercase tracking-[0.4em] mb-4 italic">Invite the Crew</h4>
                            <p class="text-stone-400 text-[10px] mb-6 leading-relaxed font-bold italic uppercase">Share the love. They get <span class="text-white">50 PTS</span> on first order, you get <span class="text-white">50 PTS</span> too.</p>
                            <div class="relative flex items-center">
                                <input type="text" readonly id="refLink" value="{{ route('register', ['ref' => $user->referral_code]) }}" 
                                    class="w-full bg-stone-950 border-stone-800 text-stone-300 rounded-xl py-3 pl-4 pr-20 text-[9px] font-bold focus:ring-amber-600 focus:border-amber-600 transition-all shadow-inner">
                                <button onclick="copyReferralLink()" class="absolute right-1.5 bg-amber-600 hover:bg-amber-500 text-stone-950 font-black px-3 py-1.5 rounded-lg text-[8px] uppercase tracking-tighter transition-all active:scale-95 shadow-lg">Copy</button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-stone-100 dark:bg-stone-900 rounded-[2.5rem] p-8 border border-stone-200 dark:border-stone-800 shadow-sm">
                        <h4 class="text-stone-400 font-black text-[10px] uppercase tracking-[0.4em] mb-4 italic">The Daily Roast</h4>
                        <p class="text-stone-500 text-[10px] mb-6 leading-relaxed font-medium italic">Maintaining a streak? You deserve a <span class="font-black text-stone-900 dark:text-white uppercase">Premium Espresso</span>.</p>
                        <a href="{{ route('home') }}" class="w-full inline-flex items-center justify-center text-[9px] font-black uppercase tracking-[0.2em] px-6 py-4 bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-950 rounded-2xl hover:bg-stone-800 transition-all shadow-md">Order Now</a>
                    </div>

                    {{-- Data Management Section --}}
                    <div class="bg-stone-900 rounded-[2.5rem] p-8 border border-stone-800 shadow-connected relative overflow-hidden group">
                        <div class="relative z-10">
                            <span class="text-[10px] font-black text-stone-500 uppercase tracking-[0.4em] block mb-4">Privacy Management</span>
                            <h4 class="text-xl font-black text-white uppercase italic tracking-tighter mb-4">Your Records</h4>
                            <p class="text-[9px] text-stone-400 font-medium italic leading-relaxed mb-8 uppercase tracking-tight">
                                Review all stored information including your brewing history and loyalty milestones in a clean, readable format.
                            </p>
                            <div class="flex flex-col gap-3">
                                <a href="{{ route('profile.data_report') }}" class="w-full py-4 bg-stone-50 text-stone-950 rounded-2xl font-black uppercase tracking-widest text-[9px] hover:bg-amber-500 hover:text-white transition-all transform active:scale-95 shadow-xl flex items-center justify-center gap-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    View Data Report
                                </a>
                                <form action="{{ route('profile.export') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-3 text-stone-500 rounded-2xl font-black uppercase tracking-widest text-[8px] border border-stone-800 hover:text-amber-500 transition-all flex items-center justify-center gap-2">
                                        Download Raw Portability Data (JSON)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-12">
                    <div class="flex items-center justify-between px-2">
                        <h3 class="font-black text-stone-900 dark:text-white uppercase tracking-tighter italic shrink-0" style="font-size: var(--fluid-18-32)">Brewing History</h3>
                        <a href="{{ route('orders.index') }}" class="text-[10px] font-black uppercase tracking-widest text-stone-400 hover:text-amber-600 transition-colors">Archive</a>
                    </div>

                    @forelse($recentOrders as $order)
                        <div class="bg-white dark:bg-stone-900 rounded-[3rem] p-6 sm:p-10 border border-stone-100 dark:border-stone-800 shadow-connected group transition-all overflow-hidden">
                            <div class="flex flex-col md:flex-row justify-between md:items-center border-b border-stone-50 dark:border-stone-800 pb-8 mb-8 gap-6">
                                <div class="flex items-center gap-5">
                                    <div class="w-16 h-16 rounded-2xl bg-stone-50 dark:bg-stone-950 border border-stone-100 dark:border-stone-800 flex items-center justify-center shrink-0 shadow-inner">
                                        @if(strtolower($order->status) == 'preparing')
                                            <svg class="w-8 h-8 animate-pulse text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        @else
                                            <svg class="w-8 h-8 text-stone-900 dark:text-stone-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <p class="font-black text-2xl sm:text-3xl text-stone-900 dark:text-white tracking-tighter uppercase italic leading-none">#{{ $order->id }}</p>
                                            <a href="{{ route('orders.receipt', $order->id) }}" class="text-[8px] bg-stone-950 text-white font-black px-2 py-1 rounded-lg uppercase tracking-widest">Receipt</a>
                                        </div>
                                        <p class="text-[10px] font-black uppercase text-stone-400 mt-2 italic whitespace-nowrap">{{ $order->created_at->format('M d, Y • h:i A') }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-row md:flex-col justify-between items-center md:items-end w-full md:w-auto mt-2 md:mt-0">
                                    <p class="text-3xl font-black text-stone-900 dark:text-white tracking-tighter leading-none italic">₱{{ number_format($order->total_price, 2) }}</p>
                                    <span class="px-4 py-1.5 bg-stone-50 dark:bg-stone-950 border border-stone-100 dark:border-stone-800 rounded-full text-[9px] font-black uppercase tracking-[0.3em] text-amber-600 italic leading-none mt-2">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-6">
                                @foreach($order->items as $item)
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 rounded-3xl bg-stone-50 dark:bg-stone-950/50 border border-stone-100 dark:border-stone-800 gap-5">
                                        <div class="flex items-center gap-5 min-w-0">
                                            <div class="w-14 h-14 rounded-2xl overflow-hidden border border-stone-100 dark:border-stone-800 bg-white shrink-0">
                                                @if($item->product && $item->product->image)<img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">@endif
                                            </div>
                                            <div class="min-w-0 truncate">
                                                <p class="font-black text-base sm:text-lg text-stone-900 dark:text-white uppercase truncate">{{ $item->product->name ?? 'Brew' }}</p>
                                                <p class="text-[10px] font-black text-stone-400 uppercase italic">{{ $item->quantity }}x • {{ $item->size }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between sm:justify-end gap-6 pt-4 sm:pt-0 border-t sm:border-none border-stone-100 dark:border-stone-800">
                                            <p class="font-black text-stone-900 dark:text-white text-xl tracking-tighter italic leading-none">₱{{ number_format($item->price * $item->quantity, 2) }}</p>
                                            @if(!$item->review && in_array(strtolower($order->status), ['completed', 'ready']))
                                                <button @click="reviewModal = true; selectedProduct = '{{ addslashes($item->product->name) }}'; selectedItemId = '{{ $item->id }}'" class="px-5 py-3 bg-stone-900 dark:bg-stone-100 text-white dark:text-stone-900 rounded-2xl text-[9px] font-black uppercase tracking-widest shadow-xl hover:bg-amber-600 transition-all shrink-0">Review</button>
                                            @elseif($item->review)
                                                <div class="flex items-center gap-1 px-3 py-1.5 bg-white dark:bg-stone-800 rounded-full border border-stone-200 dark:border-stone-700 shrink-0">
                                                    @foreach(range(1, 5) as $star)<svg class="w-2.5 h-2.5 {{ $star <= $item->review->rating ? 'text-amber-500' : 'text-stone-300 dark:text-stone-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="py-24 text-center opacity-40 italic font-black uppercase text-stone-500 tracking-widest text-[10px]">No active brews in terminal</div>
                    @endforelse

                    <div class="mt-12">
                        <div class="flex items-center justify-between px-2 mb-6">
                            <h3 class="font-black text-stone-900 dark:text-white uppercase tracking-tighter italic" style="font-size: var(--fluid-18-32)">Points Ledger</h3>
                            <span class="text-[10px] font-black uppercase tracking-widest text-stone-400">Recent Activity</span>
                        </div>
                        <div class="bg-white dark:bg-stone-900 rounded-[3rem] border border-stone-100 dark:border-stone-800 shadow-connected overflow-hidden">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-stone-50 dark:border-stone-800">
                                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-stone-400">Detail</th>
                                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-stone-400">Date</th>
                                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-stone-400 text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-50 dark:divide-stone-800">
                                    @forelse($transactions as $tx)
                                        <tr class="group hover:bg-stone-50 dark:hover:bg-stone-950/50 transition-colors">
                                            <td class="px-8 py-6">
                                                <p class="font-bold text-stone-900 dark:text-white text-sm uppercase tracking-tight">{{ $tx->description }}</p>
                                            </td>
                                            <td class="px-8 py-6">
                                                <p class="text-[10px] font-black text-stone-400 uppercase italic">{{ $tx->created_at->format('M d, Y') }}</p>
                                            </td>
                                            <td class="px-8 py-6 text-right">
                                                <span class="font-black italic {{ $tx->amount > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                                    {{ $tx->amount > 0 ? '+' : '' }}{{ $tx->amount }} PTS
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-8 py-12 text-center opacity-30 italic font-black uppercase text-stone-500 tracking-widest text-[10px]">No transactions on record</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-20 space-y-12">
                        <div class="flex items-center justify-between px-2">
                            <h3 class="font-black text-stone-900 dark:text-white uppercase tracking-tighter italic" style="font-size: var(--fluid-18-32)">Concierge History</h3>
                        </div>
                        @if(isset($supportTickets))
                            @forelse($supportTickets as $ticket)
                                <div class="bg-white dark:bg-stone-900 rounded-[2.5rem] p-8 sm:p-10 border border-stone-100 dark:border-stone-800 shadow-connected">
                                    <div class="flex flex-wrap items-center gap-3 mb-4">
                                        <span class="px-3 py-1 bg-stone-50 dark:bg-stone-950 border border-stone-100 dark:border-stone-800 rounded-full text-[8px] font-black uppercase text-stone-400">#{{ $ticket->id }}</span>
                                        <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest {{ $ticket->status == 'pending' ? 'bg-amber-500/10 text-amber-600 animate-pulse' : ($ticket->status == 'replied' ? 'bg-blue-500/10 text-blue-600' : 'bg-emerald-500/10 text-emerald-600') }}">{{ $ticket->status }}</span>
                                    </div>
                                    <h4 class="text-xl font-black text-stone-900 dark:text-white uppercase italic mb-2">{{ $ticket->subject }}</h4>
                                    <p class="text-sm text-stone-500 dark:text-stone-400 italic">"{{ $ticket->message }}"</p>
                                    @if($ticket->replies && $ticket->replies->count() > 0)
                                        <div class="mt-8 pt-8 border-t border-stone-50 dark:border-stone-800 space-y-6">
                                            @foreach($ticket->replies as $reply)
                                                <div class="flex gap-4 ml-4 sm:ml-10">
                                                    <div class="shrink-0 w-10 h-10 rounded-2xl bg-stone-900 dark:bg-stone-100 flex items-center justify-center text-white dark:text-stone-900">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                    </div>
                                                    <div class="bg-stone-50 dark:bg-stone-950/50 p-6 rounded-[2rem] border border-stone-100 dark:border-stone-800 flex-1 relative">
                                                        <div class="flex justify-between items-center mb-2">
                                                            <span class="text-[8px] font-black uppercase text-amber-600 tracking-[0.2em]">Admin Response</span>
                                                            <span class="text-[7px] text-stone-400 font-bold uppercase">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-sm text-stone-700 dark:text-stone-300 italic">{{ $reply->message }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="py-12 text-center opacity-30 italic font-black uppercase text-stone-500 tracking-widest text-[9px]">No support log found</div>
                            @endforelse
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div x-show="reviewModal" class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4 bg-stone-950/90 backdrop-blur-md" x-cloak x-transition>
            <div class="bg-white dark:bg-stone-900 w-full max-w-lg rounded-[3rem] border border-stone-200 dark:border-stone-800 shadow-2xl overflow-hidden" @click.away="reviewModal = false">
                <div class="p-8 sm:p-12">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            <h3 class="text-stone-900 dark:text-white font-black text-3xl uppercase tracking-tighter italic leading-none">Brew Journal</h3>
                            <p class="text-amber-600 text-[10px] font-black uppercase mt-3 italic" x-text="'Feedback: ' + selectedProduct"></p>
                        </div>
                        <button @click="reviewModal = false" class="p-2 text-stone-400 hover:text-stone-900 shrink-0 transition-colors"><svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-10">
                        @csrf
                        <input type="hidden" name="order_item_id" :value="selectedItemId">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-stone-500 mb-8 text-center">Select Intensity</label>
                            <div class="flex flex-wrap justify-center gap-3">
                                @foreach(range(1, 5) as $rating)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="rating" value="{{ $rating }}" class="hidden peer" required>
                                        <div class="w-12 h-12 rounded-2xl border-2 border-stone-100 dark:border-stone-800 flex items-center justify-center peer-checked:bg-amber-600 peer-checked:border-amber-600 peer-checked:text-stone-950 text-stone-400 font-black transition-all group-hover:border-amber-500 shadow-sm text-xl italic">{{ $rating }}</div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-stone-500 mb-4 px-2 italic leading-none">Authorized Journal Entry</label>
                            <textarea name="comment" rows="3" class="w-full bg-stone-50 dark:bg-stone-950 border-none rounded-[2rem] px-8 py-6 text-stone-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all text-sm italic shadow-inner" placeholder="Tell us about the magic in your cup today..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-6 bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-950 rounded-[2rem] font-black uppercase tracking-[0.2em] shadow-2xl transition-transform hover:scale-[1.02]">Authorize +2 PTS</button>
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
            btn.classList.replace('bg-amber-600', 'bg-emerald-600');
            
            setTimeout(() => {
                btn.innerText = originalText;
                btn.classList.replace('bg-emerald-600', 'bg-amber-600');
            }, 2000);
        }
    </script>
</x-app-layout>