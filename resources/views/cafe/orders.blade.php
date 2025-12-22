<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('My Order History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 flex items-center p-4 text-amber-800 border-l-4 border-amber-500 bg-amber-50 dark:bg-stone-800 dark:text-amber-500 rounded-r-xl shadow-sm transition-all">
                    <div class="flex-shrink-0 text-xl mr-3">🎉</div>
                    <div class="text-sm font-bold">{{ session('success') }}</div>
                </div>
            @endif

            @if($orders->isEmpty())
                <div class="bg-white dark:bg-stone-900 overflow-hidden shadow-sm sm:rounded-lg p-10 text-center border border-stone-100 dark:border-stone-800">
                    <p class="text-stone-500 dark:text-stone-400 text-xl mb-4 font-serif italic">You haven't placed any orders yet.</p>
                    <a href="{{ route('home') }}" class="inline-block bg-amber-600 text-white font-black uppercase tracking-widest text-xs py-4 px-8 rounded-full hover:bg-amber-700 transition transform hover:-translate-y-1 shadow-lg shadow-amber-600/20">
                        Start Your First Order
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div @class([
                            'bg-white dark:bg-stone-900 overflow-hidden shadow-sm sm:rounded-[2rem] p-8 border-l-8 transition-all border border-stone-100 dark:border-stone-800',
                            'border-l-red-500 shadow-red-500/5'   => $order->status == 'pending',
                            'border-l-amber-500 shadow-amber-500/5' => $order->status == 'preparing',
                            'border-l-green-500 shadow-green-500/5' => $order->status == 'ready' || $order->status == 'completed',
                            'border-l-stone-500 shadow-stone-500/5'  => !in_array($order->status, ['pending', 'preparing', 'ready', 'completed']),
                        ])>
                            
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center border-b border-stone-100 dark:border-stone-800 pb-6 mb-6">
                                <div class="mb-4 sm:mb-0">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400 font-black px-2 py-1 rounded uppercase tracking-widest">Order #{{ $order->id }}</span>
                                        <a href="{{ route('orders.receipt', $order->id) }}" class="text-[10px] bg-amber-600 text-white font-black px-2 py-1 rounded uppercase tracking-widest hover:bg-amber-700 transition">
                                            PDF RECEIPT
                                        </a>
                                    </div>
                                    <div class="text-sm font-serif italic text-stone-500 dark:text-stone-400 mt-2">
                                        {{ $order->created_at->format('F d, Y • h:i A') }}
                                    </div>
                                </div>
                                
                                <div class="text-left sm:text-right">
                                    <span @class([
                                        'px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest text-white inline-block',
                                        'bg-red-500 shadow-lg shadow-red-500/20'   => $order->status == 'pending',
                                        'bg-amber-500 shadow-lg shadow-amber-500/20' => $order->status == 'preparing',
                                        'bg-green-500 shadow-lg shadow-green-500/20' => $order->status == 'ready' || $order->status == 'completed',
                                        'bg-stone-500 shadow-lg shadow-stone-500/20'  => !in_array($order->status, ['pending', 'preparing', 'ready', 'completed']),
                                    ])>
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-10 px-4">
                                <div class="flex items-center w-full max-w-md mx-auto">
                                    <div class="relative flex flex-col items-center flex-1">
                                        <div class="w-3 h-3 rounded-full {{ in_array($order->status, ['pending', 'preparing', 'ready', 'completed']) ? 'bg-amber-600' : 'bg-stone-200 dark:bg-stone-800' }}"></div>
                                        <span class="text-[8px] font-black uppercase mt-2 text-stone-400">Received</span>
                                    </div>
                                    <div class="h-0.5 flex-1 {{ in_array($order->status, ['preparing', 'ready', 'completed']) ? 'bg-amber-600' : 'bg-stone-200 dark:bg-stone-800' }}"></div>
                                    <div class="relative flex flex-col items-center flex-1">
                                        <div class="w-3 h-3 rounded-full {{ in_array($order->status, ['preparing', 'ready', 'completed']) ? 'bg-amber-600' : 'bg-stone-200 dark:bg-stone-800' }}"></div>
                                        <span class="text-[8px] font-black uppercase mt-2 text-stone-400">Brewing</span>
                                    </div>
                                    <div class="h-0.5 flex-1 {{ in_array($order->status, ['ready', 'completed']) ? 'bg-amber-600' : 'bg-stone-200 dark:bg-stone-800' }}"></div>
                                    <div class="relative flex flex-col items-center flex-1">
                                        <div class="w-3 h-3 rounded-full {{ in_array($order->status, ['ready', 'completed']) ? 'bg-amber-600' : 'bg-stone-200 dark:bg-stone-800' }}"></div>
                                        <span class="text-[8px] font-black uppercase mt-2 text-stone-400">Ready</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6 space-y-6">
                                @foreach($order->items as $item)
                                    <div class="flex flex-col gap-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                <div class="w-14 h-14 bg-stone-50 dark:bg-stone-800 rounded-full overflow-hidden shrink-0 shadow-inner border border-stone-100 dark:border-stone-700">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-stone-100 dark:bg-stone-800 text-stone-400">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-bold text-stone-900 dark:text-white">{{ $item->product->name ?? 'Unknown Item' }}</p>
                                                    <p class="text-xs text-stone-500">{{ $item->quantity }}x | {{ $item->size }}</p>
                                                </div>
                                            </div>
                                            <p class="font-black text-stone-900 dark:text-white">₱{{ number_format($item->price * $item->quantity, 2) }}</p>
                                        </div>

                                        @if($order->status == 'completed' || $order->status == 'ready')
                                            @if(!$item->review)
                                                <div class="ml-16 p-6 bg-stone-50 dark:bg-stone-800/50 rounded-2xl border border-stone-100 dark:border-stone-800">
                                                    <div class="flex items-center justify-between mb-4">
                                                        <h4 class="text-[11px] font-black uppercase tracking-widest text-stone-900 dark:text-white">Rate your brew</h4>
                                                        <span class="text-[9px] font-black uppercase tracking-widest text-amber-600 bg-amber-500/10 px-2 py-1 rounded-md">+2 Points</span>
                                                    </div>
                                                    
                                                    <form action="{{ route('reviews.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                        
                                                        <div class="flex items-center gap-2 mb-4" x-data="{ rating: 0, hoverRating: 0 }">
                                                            @foreach(range(1, 5) as $i)
                                                                <button type="button" 
                                                                    @click="rating = {{ $i }}" 
                                                                    @mouseenter="hoverRating = {{ $i }}"
                                                                    @mouseleave="hoverRating = 0"
                                                                    class="focus:outline-none transition-transform hover:scale-125">
                                                                    <svg class="w-6 h-6 transition-colors duration-200" 
                                                                        :class="(hoverRating || rating) >= {{ $i }} ? 'text-amber-500' : 'text-stone-300 dark:text-stone-600'" 
                                                                        fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                    </svg>
                                                                </button>
                                                            @endforeach
                                                            <input type="hidden" name="rating" x-model="rating" required>
                                                        </div>

                                                        <textarea name="comment" placeholder="Any thoughts on your drink?" class="w-full bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-700 rounded-xl text-xs focus:ring-amber-500 mb-4 p-3 outline-none dark:text-white"></textarea>
                                                        
                                                        <button type="submit" class="bg-stone-900 dark:bg-amber-600 text-white px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-lg transition transform hover:-translate-y-0.5 active:scale-95">
                                                            Submit Feedback
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="ml-16 flex items-center gap-2">
                                                    <div class="flex">
                                                        @foreach(range(1, 5) as $star)
                                                            <svg class="w-3 h-3 {{ $star <= $item->review->rating ? 'text-amber-500' : 'text-stone-300 dark:text-stone-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @endforeach
                                                    </div>
                                                    <span class="text-[9px] font-black text-stone-400 uppercase tracking-widest italic">Feedback Provided</span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between sm:items-center pt-6 border-t border-stone-100 dark:border-stone-800">
                                <div class="text-[10px] font-black uppercase tracking-widest text-stone-400 mb-2 sm:mb-0">
                                    Payment via <span class="text-stone-700 dark:text-stone-200">{{ ucfirst($order->payment_method) }}</span>
                                </div>
                                
                                <div class="text-2xl font-black text-stone-900 dark:text-white">
                                    ₱{{ number_format($order->total_price, 2) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>