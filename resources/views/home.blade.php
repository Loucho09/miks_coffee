<x-app-layout>
    @php
        /** @var \Illuminate\Database\Eloquent\Collection $topStreaks */
        /** @var \Illuminate\Database\Eloquent\Collection $featuredProducts */
    @endphp

    <style>
        :root {
            --fluid-32-64: clamp(2rem, 5vw + 1rem, 4rem);
            --fluid-18-32: clamp(1.125rem, 3vw + 0.5rem, 2rem);
        }
        .shadow-connected { box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.5); }
    </style>

    <div class="bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500">
        <div class="relative py-24 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-20">
                    <span class="text-amber-600 font-black uppercase tracking-[0.4em] text-[10px] mb-4 block">Miks Coffee Community</span>
                    <h1 class="font-black text-stone-900 dark:text-white uppercase italic leading-none tracking-tighter mb-8" style="font-size: var(--fluid-32-64)">
                        The Daily Brew <br> Tradition
                    </h1>
                    <p class="max-w-xl mx-auto text-stone-500 dark:text-stone-400 font-medium italic text-sm mb-10">
                        Join the community of daily regulars and climb the leaderboard. Every 3-day streak earns you exclusive bonus rewards.
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('home') }}" class="px-10 py-5 bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-950 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:scale-105 transition-transform shadow-xl">
                            Order Now
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                    {{-- Featured Products Section --}}
                    <div class="lg:col-span-7 space-y-8">
                        <div class="flex items-center justify-between px-2">
                            <h3 class="font-black text-stone-900 dark:text-white uppercase tracking-tighter italic" style="font-size: var(--fluid-18-32)">Today's Roasts</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($featuredProducts as $product)
                                <div class="bg-white dark:bg-stone-900 p-6 rounded-[2.5rem] border border-stone-100 dark:border-stone-800 shadow-sm group">
                                    <div class="w-full aspect-square rounded-3xl overflow-hidden mb-6 bg-stone-50 dark:bg-stone-950">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        @endif
                                    </div>
                                    <h4 class="font-black text-stone-900 dark:text-white uppercase tracking-tight mb-2">{{ $product->name }}</h4>
                                    <span class="text-amber-600 font-black italic">PHP {{ number_format($product->price, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Public Streak Leaderboard Section --}}
                    <div class="lg:col-span-5">
                        <div class="bg-stone-900 rounded-[3rem] p-10 text-white shadow-connected border border-stone-800 sticky top-12">
                            <div class="flex items-center justify-between mb-10">
                                <h3 class="font-black text-xl uppercase tracking-tighter italic">Streak Giants</h3>
                                <span class="px-3 py-1 bg-amber-600 text-stone-950 rounded-full text-[8px] font-black uppercase tracking-widest">Live Updates</span>
                            </div>

                            <div class="space-y-6">
                                @forelse($topStreaks as $index => $leader)
                                    <div class="flex items-center justify-between p-4 rounded-2xl bg-stone-950/50 border border-stone-800 transition-all hover:border-amber-600/50">
                                        <div class="flex items-center gap-4">
                                            <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black italic text-xs {{ $index == 0 ? 'bg-amber-600 text-stone-950 shadow-lg shadow-amber-600/20' : 'bg-stone-800 text-stone-400' }}">
                                                #{{ $index + 1 }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm uppercase tracking-tight text-white">{{ $leader->name }}</p>
                                                <p class="text-[9px] font-black text-stone-500 uppercase tracking-widest">Member</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xl font-black italic text-amber-500 leading-none">{{ $leader->streak_count }}</p>
                                            <p class="text-[8px] font-black text-stone-500 uppercase tracking-widest">Day Streak</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-10 text-center opacity-30 italic font-black uppercase text-stone-500 tracking-widest text-[10px]">
                                        The leaderboard is empty. <br> Start your streak today.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>