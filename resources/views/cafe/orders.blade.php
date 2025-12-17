<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('My Order History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($orders->isEmpty())
                <div class="bg-white dark:bg-stone-900 overflow-hidden shadow-sm sm:rounded-lg p-10 text-center">
                    <p class="text-stone-500 dark:text-stone-400 text-xl mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ route('menu.index') }}" class="bg-amber-600 text-white font-bold py-2 px-4 rounded hover:bg-amber-700 transition">
                        Order Now
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div @class([
                            'bg-white dark:bg-stone-900 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 transition',
                            'border-red-500'   => $order->status == 'pending',
                            'border-amber-500' => $order->status == 'preparing',
                            'border-green-500' => $order->status == 'ready' || $order->status == 'completed',
                            'border-gray-500'  => !in_array($order->status, ['pending', 'preparing', 'ready', 'completed']),
                        ])>
                            
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center border-b border-stone-100 dark:border-stone-800 pb-4 mb-4">
                                <div class="mb-2 sm:mb-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-stone-500 dark:text-stone-400 uppercase tracking-wide">Order #{{ $order->id }}</span>
                                        <a href="{{ route('orders.receipt', $order->id) }}" class="text-xs bg-stone-100 hover:bg-stone-200 dark:bg-stone-700 text-stone-600 dark:text-stone-300 px-2 py-1 rounded border border-stone-200 dark:border-stone-600">
                                            📄 PDF
                                        </a>
                                    </div>
                                    <div class="text-sm text-stone-400 dark:text-stone-500 mt-1">
                                        {{ $order->created_at->format('M d, Y • h:i A') }}
                                    </div>
                                </div>
                                
                                <div class="text-left sm:text-right">
                                    <span @class([
                                        'px-3 py-1 rounded-full text-xs font-bold text-white uppercase inline-block',
                                        'bg-red-500'   => $order->status == 'pending',
                                        'bg-amber-500' => $order->status == 'preparing',
                                        'bg-green-500' => $order->status == 'ready' || $order->status == 'completed',
                                        'bg-gray-500'  => !in_array($order->status, ['pending', 'preparing', 'ready', 'completed']),
                                    ])>
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <ul class="list-disc list-inside text-stone-700 dark:text-stone-300 space-y-1">
                                    @foreach($order->items as $item)
                                        <li>
                                            <span class="font-bold">{{ $item->quantity }}x</span> 
                                            {{ $item->product->name ?? 'Unknown Item' }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between sm:items-center pt-4 border-t border-stone-100 dark:border-stone-800">
                                <div class="text-sm text-stone-600 dark:text-stone-400 mb-2 sm:mb-0">
                                    Payment: <span class="font-semibold">{{ ucfirst($order->payment_method) }}</span>
                                </div>
                                
                                <div class="text-xl font-bold text-stone-900 dark:text-white">
                                    Total: ₱{{ number_format($order->total_price, 2) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>