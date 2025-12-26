<x-app-layout>
    @php
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $claimablePoints = 0;
        $unreviewedItems = [];
        if(isset($recentOrders)) {
            foreach($recentOrders as $order) {
                if(in_array($order->status, ['completed', 'ready'])) {
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
    @endphp

    <div x-data="{ reviewModal: false, selectedProduct: '', selectedItemId: '' }" class="py-6 sm:py-8 lg:py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-6 flex items-center p-4 text-stone-800 dark:text-stone-200 border border-stone-200 dark:border-stone-800 bg-stone-100 dark:bg-stone-900 rounded-2xl shadow-sm transition-all">
                    <div class="flex-shrink-0 mr-3 text-stone-500 dark:text-stone-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="text-[10px] sm:text-xs font-black uppercase tracking-[0.2em]">{{ session('success') }}</div>
                </div>
            @endif

            <div class="mb-8 lg:mb-12 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div class="w-full">
                    <span class="text-stone-400 dark:text-stone-500 font-black uppercase tracking-[0.4em] text-[8px] sm:text-[10px] mb-2 block font-sans">Command Center</span>
                    <h2 class="font-black text-3xl sm:text-4xl lg:text-5xl text-stone-900 dark:text-white leading-[1.1] tracking-tighter uppercase font-sans">
                        Good {{ date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') }}, <br class="sm:hidden"> {{ $user->name }}
                    </h2>
                </div>
                <div class="flex items-center self-start md:self-auto gap-3 bg-stone-100 dark:bg-stone-900 px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl border border-stone-200 dark:border-stone-800 shadow-sm">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $user->streak_count > 1 ? 'text-amber-600 animate-pulse' : 'text-stone-400' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-1.513-1.155-2.452a10.11 10.11 0 00-.4-1.045zM10 17a1 1 0 100-2 1 1 0 000 2z" /></svg>
                    <span class="text-[10px] sm:text-xs font-black uppercase tracking-[0.2em] text-stone-800 dark:text-stone-200 whitespace-nowrap">{{ $user->streak_count ?? 1 }} Day Streak</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 sm:gap-10 items-start">
                <div class="lg:col-span-1 space-y-6 sm:space-y-10">
                    <div class="relative overflow-hidden bg-stone-900 rounded-[2rem] sm:rounded-[3rem] p-8 sm:p-10 text-white shadow-connected border border-stone-800 transition-all duration-500">
                        <div class="absolute top-0 right-0 w-32 sm:w-40 h-32 sm:h-40 bg-stone-800/40 rounded-full blur-[60px] sm:blur-[80px] -mr-16 sm:-mr-20 -mt-16 sm:-mt-20"></div>
                        <div class="relative z-10">
                            <p class="text-[8px] sm:text-[10px] uppercase tracking-[0.4em] text-stone-500 font-black mb-1 italic">Current Tier</p>
                            <p class="text-stone-300 font-black uppercase tracking-widest text-xs sm:text-sm mb-6 sm:mb-10 italic">{{ $dashTier }} Member</p>
                            <p class="text-[8px] sm:text-[10px] uppercase tracking-[0.4em] text-stone-500 font-black mb-2 sm:mb-3">Loyalty Points</p>
                            <div class="flex items-baseline gap-2 sm:gap-3 mb-4">
                                <span class="text-5xl sm:text-6xl font-black text-white tracking-tighter leading-none">{{ $dashPoints }}</span>
                                <span class="text-stone-500 font-black text-lg sm:text-xl tracking-widest uppercase">PTS</span>
                            </div>
                            <div class="w-full bg-stone-800 rounded-full h-1 sm:h-1.5 overflow-hidden shadow-inner">
                                <div class="bg-stone-400 h-full rounded-full transition-all duration-1000" :style="{ width: '{{ $perc }}%' }"></div>
                            </div>
                        </div>
                    </div>

                    @if($claimablePoints > 0)
                        <div class="bg-amber-500 dark:bg-amber-600 rounded-[2rem] sm:rounded-[2.5rem] p-6 sm:p-8 relative overflow-hidden shadow-connected group">
                            <div class="relative z-10 text-stone-950">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-stone-950/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    </div>
                                    <span class="px-2.5 py-1 bg-stone-950 text-white rounded-full text-[7px] sm:text-[8px] font-black uppercase tracking-widest">Rewards waiting</span>
                                </div>
                                <h4 class="font-black text-xl sm:text-2xl uppercase tracking-tighter mb-2 italic">+{{ $claimablePoints }} PTS AVAILABLE</h4>
                                <p class="text-[9px] sm:text-[10px] mb-6 sm:mb-8 leading-relaxed font-bold uppercase tracking-tight opacity-80">You have {{ count($unreviewedItems) }} brews to rate. Claim your loyalty points now.</p>
                                <a href="#order-history-main" class="w-full inline-flex items-center justify-center text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] px-5 sm:px-6 py-3.5 sm:py-4 bg-stone-950 text-white rounded-xl sm:rounded-2xl hover:scale-[1.02] transition-all shadow-xl">Review & Claim Now</a>
                            </div>
                        </div>
                    @endif

                    <div class="bg-stone-100 dark:bg-stone-900 rounded-[2rem] sm:rounded-[2.5rem] p-6 sm:p-8 border border-stone-200 dark:border-stone-800 shadow-sm h-fit">
                        <h4 class="text-stone-900 dark:text-stone-300 font-black text-[10px] sm:text-xs uppercase tracking-[0.4em] mb-3 sm:mb-4 italic">The Daily Roast</h4>
                        <p class="text-stone-500 dark:text-stone-400 text-[10px] sm:text-xs mb-5 sm:mb-6 leading-relaxed font-medium italic">Maintaining a streak? You deserve a <span class="font-black text-stone-900 dark:text-white uppercase">Premium Espresso</span>.</p>
                        <a href="{{ route('home') }}" class="w-full inline-flex items-center justify-center text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] px-5 sm:px-6 py-3.5 sm:py-4 bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-950 rounded-xl sm:rounded-2xl hover:bg-stone-800 dark:hover:bg-white transition-all shadow-md">Order Now</a>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-12 sm:space-y-20">
                    <div id="order-history-main" class="space-y-6 sm:space-y-10">
                        <div class="flex items-center justify-between px-2 sm:px-6">
                            <h3 class="font-black text-2xl sm:text-3xl text-stone-900 dark:text-white uppercase tracking-tighter italic leading-none">Order & Brewing History</h3>
                            <a href="{{ route('orders.index') }}" class="text-[8px] sm:text-[10px] font-black uppercase tracking-[0.3em] text-stone-400 hover:text-amber-600 transition-colors">Archive</a>
                        </div>

                        @forelse($recentOrders as $order)
                            <div class="bg-white dark:bg-stone-900 overflow-hidden shadow-connected rounded-[2rem] sm:rounded-[3rem] p-6 sm:p-8 md:p-12 border border-stone-100 dark:border-stone-800 group transition-all">
                                <div class="flex flex-col md:flex-row justify-between md:items-center border-b border-stone-50 dark:border-stone-800 pb-6 sm:pb-10 mb-6 sm:mb-10 gap-6">
                                    <div class="flex items-center gap-4 sm:gap-6">
                                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-stone-50 dark:bg-stone-950 flex items-center justify-center border border-stone-100 dark:border-stone-800 shadow-inner group-hover:border-amber-500/30 transition-colors">
                                            @if($order->status == 'pending')
                                                <svg class="w-6 h-6 sm:w-8 sm:h-8 animate-spin opacity-40 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            @elseif($order->status == 'preparing')
                                                <svg class="w-6 h-6 sm:w-8 sm:h-8 animate-pulse text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            @else
                                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-stone-900 dark:text-stone-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                                                <p class="font-black text-2xl sm:text-3xl text-stone-900 dark:text-white tracking-tighter uppercase italic leading-none">#{{ $order->id }}</p>
                                                <a href="{{ route('orders.receipt', $order->id) }}" class="text-[7px] sm:text-[9px] bg-stone-950 dark:bg-stone-800 text-white font-black px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl uppercase tracking-[0.2em] hover:bg-amber-600 transition-colors whitespace-nowrap">Receipt PDF</a>
                                            </div>
                                            <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest text-stone-400 mt-2 sm:mt-3 leading-none italic">{{ $order->created_at->format('M d, Y • h:i A') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-row md:flex-col justify-between items-center md:items-end w-full md:w-auto">
                                        <p class="text-2xl sm:text-3xl font-black text-stone-900 dark:text-white tracking-tighter leading-none mb-0 md:mb-2 italic">₱{{ number_format($order->total_price, 2) }}</p>
                                        <span class="px-3 sm:px-4 py-1 sm:py-1.5 bg-stone-50 dark:bg-stone-950 border border-stone-100 dark:border-stone-800 rounded-full text-[7px] sm:text-[9px] font-black uppercase tracking-[0.3em] text-amber-600 italic leading-none">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-10 sm:mb-14 px-2 sm:px-4">
                                    <div class="flex items-center w-full max-w-xl mx-auto relative h-10">
                                        <div class="absolute left-0 top-[1.2rem] w-full h-0.5 bg-stone-100 dark:bg-stone-800 rounded-full"></div>
                                        @php
                                            $prog = $order->status == 'pending' ? '15%' : ($order->status == 'preparing' ? '50%' : '100%');
                                            $isLive = in_array($order->status, ['pending', 'preparing']);
                                        @endphp
                                        <div class="absolute left-0 top-[1.2rem] h-0.5 bg-amber-500 rounded-full transition-all duration-1000 {{ $isLive ? 'animate-pulse shadow-[0_0_15px_rgba(245,158,11,0.4)]' : '' }}" :style="{ width: '{{ $prog }}' }"></div>

                                        <div class="relative z-10 flex justify-between w-full">
                                            @foreach(['Placed', 'Brewing', 'Ready'] as $index => $label)
                                                @php
                                                    $active = ($index == 0 && in_array($order->status, ['pending', 'preparing', 'ready', 'completed'])) ||
                                                             ($index == 1 && in_array($order->status, ['preparing', 'ready', 'completed'])) ||
                                                             ($index == 2 && in_array($order->status, ['ready', 'completed']));
                                                @endphp
                                                <div class="flex flex-col items-center">
                                                    <div class="w-3 h-3 sm:w-4 sm:h-4 rounded-full border-2 transition-colors duration-500 {{ $active ? 'bg-amber-500 border-amber-600' : 'bg-stone-200 dark:bg-stone-800 border-stone-300 dark:border-stone-700' }}"></div>
                                                    <span class="text-[7px] sm:text-[9px] font-black uppercase mt-3 sm:mt-4 tracking-widest text-stone-400 text-center">{{ $label }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4 sm:space-y-6 lg:space-y-8">
                                    @foreach($order->items as $item)
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-6 rounded-2xl sm:rounded-3xl bg-stone-50 dark:bg-stone-950/50 border border-stone-100 dark:border-stone-800 group/item hover:border-amber-500/20 transition-all gap-4">
                                            <div class="flex items-center gap-4 sm:gap-6">
                                                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl overflow-hidden border border-stone-100 dark:border-stone-800 bg-white dark:bg-stone-900 shadow-inner shrink-0">
                                                    @if($item->product && $item->product->image)<img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover" alt="{{ $item->product->name }}">@endif
                                                </div>
                                                <div>
                                                    <p class="font-black text-sm sm:text-lg text-stone-900 dark:text-white uppercase tracking-tight leading-none mb-1 sm:mb-2">{{ $item->product->name ?? 'Unknown Item' }}</p>
                                                    <p class="text-[8px] sm:text-[10px] font-black text-stone-400 uppercase tracking-[0.2em] italic leading-none">{{ $item->quantity }}x • {{ $item->size }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between sm:justify-end gap-4 sm:gap-6 border-t sm:border-none pt-4 sm:pt-0">
                                                <p class="font-black text-stone-900 dark:text-white text-lg sm:text-xl tracking-tighter italic leading-none">₱{{ number_format($item->price * $item->quantity, 2) }}</p>
                                                @if(!$item->review && in_array($order->status, ['completed', 'ready']))
                                                    <button @click="reviewModal = true; selectedProduct = '{{ $item->product->name }}'; selectedItemId = '{{ $item->id }}'" class="flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-2.5 sm:py-3 bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-950 rounded-xl sm:rounded-2xl text-[8px] sm:text-[10px] font-black uppercase tracking-widest hover:bg-amber-600 transition-all shadow-xl active:scale-95 leading-none">Claim +2PTS</button>
                                                @elseif($item->review)
                                                    <div class="flex items-center gap-1 px-3 py-1.5 bg-stone-100 dark:bg-stone-800 rounded-full border border-stone-200 dark:border-stone-700 shrink-0">
                                                        @foreach(range(1, 5) as $star)<svg class="w-2 h-2 sm:w-2.5 sm:h-2.5 {{ $star <= $item->review->rating ? 'text-amber-500' : 'text-stone-300 dark:text-stone-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="p-12 sm:p-24 text-center bg-white dark:bg-stone-900 rounded-[2.5rem] sm:rounded-[4rem] border border-stone-100 dark:border-stone-800 shadow-connected">
                                <h4 class="text-stone-900 dark:text-white font-black text-xl sm:text-2xl uppercase tracking-tighter mb-4 italic opacity-30 leading-tight">No active brews in progress</h4>
                                <a href="{{ route('home') }}" class="inline-flex px-8 sm:px-12 py-4 sm:py-5 bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-950 rounded-2xl sm:rounded-[2rem] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-xl text-[10px] sm:text-sm">Order Your First Brew</a>
                            </div>
                        @endforelse
                    </div>

                    <div id="support-history" class="space-y-6 sm:space-y-10">
                        <div class="flex items-center justify-between px-2 sm:px-6">
                            <h3 class="font-black text-2xl sm:text-3xl text-stone-900 dark:text-white uppercase tracking-tighter italic leading-none">Support & Concierge</h3>
                            <a href="{{ route('support.index') }}" class="text-[8px] sm:text-[10px] font-black uppercase tracking-[0.3em] text-stone-400 hover:text-amber-600 transition-colors">New Ticket</a>
                        </div>

                        @if(isset($supportTickets))
                            @forelse($supportTickets as $ticket)
                                <div class="bg-white dark:bg-stone-900 rounded-[2.5rem] sm:rounded-[3.5rem] p-6 sm:p-10 border border-stone-100 dark:border-stone-800 shadow-connected group transition-all">
                                    <div class="flex flex-col md:flex-row justify-between gap-6 mb-8">
                                        <div class="flex-1">
                                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                                <span class="px-3 py-1 bg-stone-50 dark:bg-stone-950 border border-stone-100 dark:border-stone-800 rounded-full text-[8px] font-black uppercase tracking-widest text-stone-400">#{{ $ticket->id }}</span>
                                                <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest 
                                                    {{ $ticket->status == 'pending' ? 'bg-amber-500/10 text-amber-600' : ($ticket->status == 'replied' ? 'bg-blue-500/10 text-blue-600' : 'bg-emerald-500/10 text-emerald-600') }}">
                                                    {{ $ticket->status }}
                                                </span>
                                            </div>
                                            <h4 class="text-xl font-black text-stone-900 dark:text-white uppercase tracking-tight mb-2 italic">{{ $ticket->subject }}</h4>
                                            <p class="text-sm text-stone-500 dark:text-stone-400 italic leading-relaxed">"{{ $ticket->message }}"</p>
                                        </div>
                                        <div class="text-[9px] font-black uppercase tracking-widest text-stone-300 dark:text-stone-700 italic">
                                            {{ $ticket->created_at->format('M d, Y') }}
                                        </div>
                                    </div>

                                    @if($ticket->replies->count() > 0)
                                        <div class="space-y-6 pt-8 border-t border-stone-50 dark:border-stone-800">
                                            @foreach($ticket->replies as $reply)
                                                <div class="flex gap-4 sm:gap-6 ml-4 sm:ml-10">
                                                    <div class="shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-2xl bg-stone-900 dark:bg-stone-100 flex items-center justify-center text-white dark:text-stone-900 shadow-lg">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                    </div>
                                                    <div class="bg-stone-50 dark:bg-stone-950/50 p-5 sm:p-6 rounded-[1.5rem] sm:rounded-[2rem] border border-stone-100 dark:border-stone-800 flex-1 relative">
                                                        <div class="absolute -left-2 top-4 w-4 h-4 bg-stone-50 dark:bg-stone-950/50 rotate-45 border-l border-b border-stone-100 dark:border-stone-800"></div>
                                                        <div class="flex justify-between items-center mb-3">
                                                            <span class="text-[8px] font-black uppercase text-amber-600 tracking-[0.2em]">Admin Response</span>
                                                            <span class="text-[7px] text-stone-400 font-bold uppercase">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs sm:text-sm text-stone-700 dark:text-stone-300 font-medium italic leading-relaxed">
                                                            {{ $reply->message }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="p-12 text-center bg-white dark:bg-stone-900 rounded-[2.5rem] sm:rounded-[4rem] border border-dashed border-stone-200 dark:border-stone-800 opacity-40">
                                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-stone-400 italic">No support tickets found</p>
                                </div>
                            @endforelse
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div x-show="reviewModal" class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4 bg-stone-950/80 backdrop-blur-md" x-cloak x-transition>
            <div class="bg-white dark:bg-stone-900 w-full max-w-lg rounded-[2.5rem] sm:rounded-[3.5rem] border border-stone-200 dark:border-stone-800 shadow-2xl overflow-hidden" @click.away="reviewModal = false">
                <div class="p-8 sm:p-12">
                    <div class="flex justify-between items-start mb-8 sm:mb-10">
                        <div>
                            <h3 class="text-stone-900 dark:text-white font-black text-2xl sm:text-3xl uppercase tracking-tighter italic leading-none">Rate your brew</h3>
                            <p class="text-amber-600 text-[9px] sm:text-[10px] font-bold uppercase tracking-[0.2em] mt-3 sm:mt-4 italic" x-text="'Drafting feedback for: ' + selectedProduct"></p>
                        </div>
                        <button @click="reviewModal = false" class="p-2 text-stone-400 hover:text-stone-900 dark:hover:text-white transition-colors">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_item_id" :value="selectedItemId">
                        <div class="mb-10 sm:mb-12">
                            <label class="block text-[8px] sm:text-[10px] font-black uppercase tracking-[0.4em] text-stone-500 mb-6 sm:mb-8 text-center leading-none">Select Intensity</label>
                            <div class="flex flex-wrap justify-center gap-2 sm:gap-4">
                                @foreach(range(1, 5) as $rating)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="rating" value="{{ $rating }}" class="hidden peer" required>
                                        <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl border-2 border-stone-100 dark:border-stone-800 flex items-center justify-center peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-stone-950 text-stone-400 font-black transition-all group-hover:border-amber-500 shadow-sm text-lg sm:text-xl italic">{{ $rating }}</div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-10 sm:mb-12">
                            <label class="block text-[8px] sm:text-[10px] font-black uppercase tracking-[0.4em] text-stone-500 mb-3 sm:mb-4 px-2 leading-none">Brew Journal entry</label>
                            <textarea name="comment" rows="3" class="w-full bg-stone-50 dark:bg-stone-950 border-none rounded-2xl sm:rounded-[2rem] px-6 sm:px-8 py-5 sm:py-6 text-stone-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all placeholder:text-stone-400/50 text-xs sm:text-sm font-medium italic" placeholder="How was the magic in your cup today?"></textarea>
                        </div>
                        <button type="submit" class="w-full py-5 sm:py-7 bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-950 rounded-[2rem] sm:rounded-[2.5rem] font-black uppercase tracking-[0.2em] hover:bg-amber-600 transition-all shadow-xl active:scale-95 text-[10px] sm:text-xs">Finalize & Earn +2 PTS</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        @media (max-width: 640px) {
            .shadow-connected {
                box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.1);
            }
        }
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>