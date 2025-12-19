<div wire:poll.5s> {{-- Auto-refresh logic --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-20 right-6 z-50 p-4 bg-green-600 text-white rounded-2xl shadow-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($orders as $order)
            <div wire:key="order-{{ $order->id }}-{{ $order->status }}" 
                 class="bg-white dark:bg-stone-900 rounded-[2rem] shadow-xl border border-stone-100 dark:border-stone-800 p-6">
                
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-bold text-xl dark:text-white">#{{ $order->id }}</h3>
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-600">
                        {{ $order->status }}
                    </span>
                </div>

                <div class="space-y-2 mb-6 bg-stone-50 dark:bg-stone-800 p-4 rounded-xl">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-sm dark:text-stone-300">
                            <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                            <span class="font-bold text-amber-600 uppercase">{{ $item->size }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-col gap-2">
                    @if($order->status == 'pending')
                        <button wire:click="updateStatus({{ $order->id }}, 'preparing')" 
                                class="w-full py-4 bg-stone-900 dark:bg-white text-white dark:text-stone-900 rounded-xl font-bold hover:bg-amber-600 transition">
                            Start Preparing
                        </button>
                    @elseif($order->status == 'preparing')
                        <button wire:click="updateStatus({{ $order->id }}, 'ready')" 
                                class="w-full py-4 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition">
                            Mark as Ready
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center text-stone-400 font-bold">
                Waiting for new orders... ☕
            </div>
        @endforelse
    </div>
</div>