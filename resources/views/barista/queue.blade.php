<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-serif italic text-3xl text-stone-900 dark:text-white leading-tight">
                    {{ __('Kitchen Display System') }}
                </h2>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-amber-600 mt-1">Live Order Command</p>
            </div>
            <div class="flex items-center gap-2 bg-stone-100 dark:bg-stone-900 px-4 py-2 rounded-full border border-stone-200 dark:border-stone-800 shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-[9px] font-black text-stone-500 uppercase tracking-widest leading-none">System Live</span>
            </div>
        </div>
    </x-slot>

    {{-- 🟢 Audio Element for Notifications --}}
    <audio id="notification-sound" src="https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3" preload="auto"></audio>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500"
         x-data="{ 
            orders: [],
            redemptions: [],
            loading: true,
            async fetchData() {
                try {
                    // Sync Orders
                    let resOrders = await fetch('{{ route('barista.active_orders') }}');
                    let newOrders = await resOrders.json();
                    
                    // Sync Redemptions
                    let resRedeem = await fetch('{{ route('barista.active_redemptions') }}');
                    let newRedemptions = await resRedeem.json();
                    
                    // 🟢 Check for new items to play sound
                    if (!this.loading) {
                        if (newOrders.length > this.orders.length || newRedemptions.length > this.redemptions.length) {
                            let sound = document.getElementById('notification-sound');
                            if (sound) {
                                sound.currentTime = 0;
                                sound.play().catch(e => console.log('Audio wait for interaction: Click page to enable sounds'));
                            }
                        }
                    }

                    this.orders = newOrders;
                    this.redemptions = newRedemptions;
                    this.loading = false;
                } catch (e) { console.error('KDS Sync Error'); }
            }
         }" x-init="fetchData(); setInterval(() => fetchData(), 5000)" x-cloak @click="document.getElementById('notification-sound').load()">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-8 p-5 bg-stone-900 border border-stone-800 rounded-[2rem] shadow-2xl flex items-center gap-4 transition-all">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="text-[10px] sm:text-xs font-black uppercase tracking-[0.2em] text-stone-200">{{ session('success') }}</div>
                </div>
            @endif

            {{-- 1. Active Coffee Orders Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 sm:gap-10 mb-24">
                <template x-for="order in orders" :key="order.id">
                    <div class="bg-white dark:bg-stone-900 rounded-[3rem] p-8 sm:p-10 border border-stone-100 dark:border-stone-800 shadow-2xl transition-all hover:border-amber-600/30 group">
                        <div class="flex justify-between items-start mb-10">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-stone-50 dark:bg-stone-950 border border-stone-100 dark:border-stone-800 flex items-center justify-center shrink-0">
                                    <span class="font-black text-2xl text-stone-900 dark:text-white" x-text="'#' + order.id"></span>
                                </div>
                                <div>
                                    <p class="text-[8px] font-black text-stone-400 uppercase tracking-[0.4em] mb-1">Received At</p>
                                    <p class="text-xs font-bold text-stone-900 dark:text-stone-300 uppercase tracking-widest" x-text="new Date(order.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></p>
                                </div>
                            </div>
                            <span class="px-4 py-1.5 rounded-full text-[8px] font-black uppercase tracking-[0.3em] shadow-sm"
                                  :class="{
                                      'bg-amber-500 text-stone-950 animate-pulse': order.status === 'pending',
                                      'bg-blue-600 text-white': order.status === 'preparing',
                                      'bg-emerald-600 text-white': order.status === 'ready'
                                  }"
                                  x-text="order.status"></span>
                        </div>

                        <div class="space-y-4 mb-10 min-h-[160px]">
                            <template x-for="item in order.items" :key="item.id">
                                <div class="flex justify-between items-center bg-stone-50 dark:bg-stone-950/50 p-5 rounded-[1.5rem] border border-stone-100 dark:border-stone-800">
                                    <div class="flex items-center gap-5">
                                        <div class="flex flex-col items-center justify-center min-w-[2rem]">
                                            <span class="font-black text-amber-600 text-xl leading-none" x-text="item.quantity"></span>
                                            <span class="text-[7px] font-black text-stone-400 uppercase">Qty</span>
                                        </div>
                                        <div>
                                            <p class="font-black text-stone-900 dark:text-white uppercase text-xs sm:text-sm tracking-tight leading-tight" x-text="item.product.name"></p>
                                            <p class="text-[8px] font-bold text-stone-400 uppercase tracking-widest mt-1" x-text="item.size"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="pt-6 border-t border-stone-50 dark:border-stone-800">
                            <form :action="'/barista/update-status/' + order.id" method="POST">
                                @csrf
                                {{-- 🟢 Logic updated to handle Served/Completed state --}}
                                <input type="hidden" name="status" :value="order.status === 'pending' ? 'preparing' : (order.status === 'preparing' ? 'ready' : 'served')">
                                <button type="submit" class="w-full py-5 rounded-[2rem] font-black uppercase tracking-[0.3em] text-[10px] shadow-xl active:scale-95 transition-all"
                                        :class="{
                                            'bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-950 hover:bg-amber-600': order.status === 'pending',
                                            'bg-amber-600 text-white hover:bg-amber-700': order.status === 'preparing',
                                            'bg-emerald-600 text-white hover:bg-emerald-700': order.status === 'ready'
                                        }">
                                    <span x-text="order.status === 'pending' ? 'Start Roasting' : (order.status === 'preparing' ? 'Mark as Ready' : 'Complete & Serve')"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </template>
            </div>

            {{-- 2. Reward Redemption Terminal --}}
            <div class="mt-24">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 rounded-2xl bg-amber-600 flex items-center justify-center text-stone-950 shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-stone-900 dark:text-white uppercase italic tracking-tighter leading-none">Redemption Terminal</h3>
                        <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mt-1">Verify and Fulfill Voucher Claims</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <template x-for="claim in redemptions" :key="claim.id">
                        <div class="bg-white dark:bg-stone-900 rounded-[2.5rem] p-8 border-2 border-amber-600/20 shadow-2xl relative overflow-hidden group">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-full bg-stone-100 dark:bg-stone-950 border border-stone-200 dark:border-stone-800 flex items-center justify-center font-black text-stone-900 dark:text-white">
                                    <span x-text="claim.user.name.charAt(0)"></span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-stone-900 dark:text-white uppercase truncate" x-text="claim.user.name"></p>
                                    <p class="text-[9px] font-bold text-stone-500 uppercase italic">Claimed Recently</p>
                                </div>
                            </div>

                            <div class="bg-stone-50 dark:bg-stone-950 rounded-2xl p-5 border border-stone-100 dark:border-stone-800 mb-8 shadow-inner">
                                <p class="text-[8px] font-black text-stone-400 uppercase tracking-widest mb-1">Requested Item</p>
                                <p class="text-lg font-black text-amber-600 uppercase italic leading-tight" x-text="claim.description.replace('Redeemed: ', '')"></p>
                            </div>

                            <form :action="'/barista/redemption/' + claim.id + '/fulfill'" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-black uppercase tracking-widest text-[9px] shadow-lg hover:bg-emerald-700 active:scale-95 transition-all">
                                    Fulfill Reward
                                </button>
                            </form>
                        </div>
                    </template>

                    <div x-show="!loading && redemptions.length === 0" class="col-span-full py-20 text-center opacity-30 border-2 border-dashed border-stone-200 dark:border-stone-800 rounded-[3rem]">
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-stone-500 italic">No Active Redemptions Logged</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
    </style>
</x-app-layout>