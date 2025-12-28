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

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-8 p-5 bg-stone-900 border border-stone-800 rounded-[2rem] shadow-2xl flex items-center gap-4 transition-all">
                    <div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="text-[10px] sm:text-xs font-black uppercase tracking-[0.2em] text-stone-200">{{ session('success') }}</div>
                </div>
            @endif

            {{-- LIVE FEED COMPONENT --}}
            <div x-data="{ 
                orders: [],
                loading: true,
                async fetchOrders() {
                    try {
                        let response = await fetch('{{ route('barista.active_orders') }}');
                        this.orders = await response.json();
                        this.loading = false;
                    } catch (e) {
                        console.error('KDS Sync Error');
                    }
                }
            }" x-init="fetchOrders(); setInterval(() => fetchOrders(), 5000)" x-cloak>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 sm:gap-10">
                    {{-- Order Card Template --}}
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
                                      :class="order.status === 'pending' ? 'bg-amber-500 text-stone-950 animate-pulse' : 'bg-blue-600 text-white'"
                                      x-text="order.status"></span>
                            </div>

                            {{-- Items Section --}}
                            <div class="space-y-4 mb-10 min-h-[160px]">
                                <template x-for="item in order.items" :key="item.id">
                                    <div class="flex justify-between items-center bg-stone-50 dark:bg-stone-950/50 p-5 rounded-[1.5rem] border border-stone-100 dark:border-stone-800 group-hover:border-stone-200 dark:group-hover:border-stone-700 transition-colors">
                                        <div class="flex items-center gap-5">
                                            <div class="flex flex-col items-center justify-center min-w-[2rem]">
                                                <span class="font-black text-amber-600 text-xl leading-none" x-text="item.quantity"></span>
                                                <span class="text-[7px] font-black text-stone-400 uppercase">Qty</span>
                                            </div>
                                            <div class="h-8 w-px bg-stone-200 dark:bg-stone-800"></div>
                                            <div>
                                                <p class="font-black text-stone-900 dark:text-white uppercase text-xs sm:text-sm tracking-tight leading-tight" x-text="item.product.name"></p>
                                                <p class="text-[8px] font-bold text-stone-400 uppercase tracking-widest mt-1" x-text="item.size"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- Action Interface --}}
                            <div class="pt-6 border-t border-stone-50 dark:border-stone-800">
                                <form :action="'/barista/update-status/' + order.id" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" :value="order.status === 'pending' ? 'preparing' : 'ready'">
                                    <button type="submit" 
                                            class="w-full py-5 rounded-[2rem] font-black uppercase tracking-[0.3em] text-[10px] transition-all shadow-xl active:scale-95 group/btn overflow-hidden relative"
                                            :class="order.status === 'pending' ? 'bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-950 hover:bg-amber-600 dark:hover:bg-amber-500' : 'bg-amber-600 text-white hover:bg-amber-700'">
                                        <span class="relative z-10 flex items-center justify-center gap-3">
                                            <span x-text="order.status === 'pending' ? 'Start Roasting' : 'Mark as Ready'"></span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </template>

                    {{-- Empty State --}}
                    <div x-show="!loading && orders.length === 0" class="col-span-full py-32 text-center bg-white dark:bg-stone-900 rounded-[4rem] border-2 border-dashed border-stone-200 dark:border-stone-800 transition-all duration-700">
                        <div class="mb-8 opacity-10 flex justify-center">
                            <svg class="w-24 h-24 text-stone-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h4 class="text-stone-900 dark:text-white font-black text-2xl uppercase tracking-tighter italic mb-2">Queue is Empty</h4>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-stone-400 italic">No brews currently in command</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        /* High-End Shadow for KDS Cards */
        .shadow-2xl {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
    </style>
</x-app-layout>