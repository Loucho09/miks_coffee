<div wire:poll.5s> {{-- Auto-refresh logic --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-24 right-6 z-50 p-4 bg-green-600 text-white rounded-2xl shadow-xl flex items-center gap-3 font-black uppercase text-[10px] tracking-widest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($orders as $order)
            <div wire:key="order-{{ $order->id }}-{{ $order->status }}" 
                 class="bg-white dark:bg-stone-900 rounded-[2.5rem] shadow-xl border border-stone-100 dark:border-stone-800 p-8 flex flex-col transition-all duration-300 hover:shadow-2xl">
                
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="font-black text-2xl text-stone-900 dark:text-white tracking-tighter">#{{ $order->id }}</h3>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                            <span class="text-[9px] font-black uppercase tracking-widest text-stone-400">
                                In Queue: {{ $order->created_at->diffForHumans(null, true) }}
                            </span>
                        </div>
                    </div>
                    <span @class([
                        'px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] shadow-sm',
                        'bg-amber-100 text-amber-600 dark:bg-amber-900/30' => $order->status == 'pending',
                        'bg-rose-100 text-rose-600 dark:bg-rose-900/30' => $order->status == 'preparing',
                    ])>
                        {{ $order->status }}
                    </span>
                </div>

                <div class="space-y-4 mb-8 bg-stone-50 dark:bg-stone-800/50 p-6 rounded-[2rem] border border-stone-100 dark:border-stone-800">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-stone-200 dark:bg-stone-700 overflow-hidden shrink-0 border border-stone-300 dark:border-stone-600">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-stone-800 dark:text-stone-200">{{ $item->quantity }}x {{ $item->product->name }}</span>
                                    <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest">{{ $item->size }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-auto">
                    @if($order->status == 'pending')
                        <button wire:click="updateStatus({{ $order->id }}, 'preparing')" 
                                class="w-full py-4 bg-stone-900 dark:bg-amber-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-[0.3em] hover:bg-amber-700 transition-all shadow-lg shadow-amber-600/10 active:scale-95">
                            Start Preparation
                        </button>
                    @elseif($order->status == 'preparing')
                        <button wire:click="updateStatus({{ $order->id }}, 'ready')" 
                                class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-[0.3em] hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/10 active:scale-95">
                            Mark as Ready
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-32 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 rounded-full bg-stone-100 dark:bg-stone-900 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-serif italic text-stone-400">The counter is currently quiet.</h3>
                <p class="text-[10px] font-black uppercase tracking-widest text-stone-500 mt-2">New orders will appear here automatically.</p>
            </div>
        @endforelse
    </div>
</div>