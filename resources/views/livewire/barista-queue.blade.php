<div wire:poll.5s> 
    @if (session('success'))
        <div class="mb-6 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg border border-green-200 dark:border-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($orders as $order)
            <div wire:key="order-{{ $order->id }}" class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-stone-200 dark:border-stone-800 overflow-hidden flex flex-col h-full transition-all duration-300">
                
                <div class="px-6 py-4 border-b border-stone-100 dark:border-stone-800 flex justify-between items-center 
                    {{ $order->status === 'pending' ? 'bg-red-50 dark:bg-red-900/20' : ($order->status === 'preparing' ? 'bg-amber-50 dark:bg-amber-900/20' : 'bg-green-50 dark:bg-green-900/20') }}">
                    <h3 class="font-bold text-lg text-stone-900 dark:text-white">Order #{{ $order->id }}</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase 
                        {{ $order->status === 'pending' ? 'bg-red-100 text-red-700' : ($order->status === 'preparing' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                        {{ $order->status }}
                    </span>
                </div>

                <div class="p-6 flex-1">
                    <p class="text-sm text-stone-500 dark:text-stone-400 mb-4">
                        Customer: <span class="font-bold text-stone-800 dark:text-stone-200">{{ $order->customer_name ?? $order->user->name ?? 'Guest' }}</span>
                    </p>
                    
                    <ul class="space-y-3 mb-6">
                        @foreach ($order->items as $item)
                            <li class="flex justify-between items-center text-stone-800 dark:text-stone-200 text-sm border-b border-dashed border-stone-100 dark:border-stone-800 pb-2 last:border-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-amber-600">{{ $item->quantity }}x</span> 
                                    <span>{{ $item->product_name ?? $item->product->name }}</span>
                                </div>

                                @if(!empty($item->size) && $item->size !== 'Regular')
                                    <span class="bg-stone-100 dark:bg-stone-700 text-stone-600 dark:text-stone-300 text-[10px] uppercase font-bold px-2 py-0.5 rounded border border-stone-200 dark:border-stone-600">
                                        {{ $item->size }}
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="p-4 bg-stone-50 dark:bg-stone-950 border-t border-stone-100 dark:border-stone-800 mt-auto">
                    @if ($order->status === 'pending')
                        <button wire:click="updateStatus({{ $order->id }}, 'preparing')" 
                                wire:loading.attr="disabled"
                                class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-xl transition shadow-md flex justify-center items-center gap-2 disabled:opacity-50">
                            <span wire:loading.remove wire:target="updateStatus({{ $order->id }}, 'preparing')">Start Preparing</span>
                            <span wire:loading wire:target="updateStatus({{ $order->id }}, 'preparing')">Updating...</span>
                        </button>

                    @elseif ($order->status === 'preparing')
                        <button wire:click="updateStatus({{ $order->id }}, 'ready')" 
                                wire:loading.attr="disabled"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition shadow-md flex justify-center items-center gap-2 disabled:opacity-50">
                            <span wire:loading.remove wire:target="updateStatus({{ $order->id }}, 'ready')">Mark Ready</span>
                            <span wire:loading wire:target="updateStatus({{ $order->id }}, 'ready')">Updating...</span>
                        </button>
                    @endif
                </div>

            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-xl font-bold text-stone-500 dark:text-stone-400">All caught up!</p>
                <p class="text-stone-400">No active orders in queue.</p>
            </div>
        @endforelse
    </div>
</div>