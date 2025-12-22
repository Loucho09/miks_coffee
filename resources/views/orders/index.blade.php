@foreach($orders as $order)
    <div class="mb-10 border-b pb-10 border-stone-200 dark:border-stone-700">
        <h3 class="text-lg font-bold text-stone-900 dark:text-white mb-4">Order #{{ $order->id }}</h3>
        
        @foreach($order->items as $item) <div class="mt-6 p-6 bg-stone-50 dark:bg-stone-800 rounded-2xl border border-stone-100 dark:border-stone-700">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-sm font-bold text-stone-900 dark:text-white">Rate your {{ $item->product->name }}</h4>
                    <p class="text-[10px] text-stone-500">Earn 2pts for sharing your experience!</p>
                </div>
                <span class="text-[9px] font-black uppercase tracking-widest text-amber-600 bg-amber-500/10 px-2 py-1 rounded-md">+2 Loyalty Points</span>
            </div>
            
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                
                <div class="flex items-center gap-2 mb-4" x-data="{ rating: 0, hoverRating: 0 }">
                    @foreach(range(1, 5) as $i)
                        <button type="button" 
                            @click="rating = {{ $i }}" 
                            @mouseenter="hoverRating = {{ $i }}"
                            @mouseleave="hoverRating = 0"
                            class="focus:outline-none transition-transform hover:scale-125">
                            <svg class="w-8 h-8 transition-colors duration-200" 
                                :class="(hoverRating || rating) >= {{ $i }} ? 'text-amber-500' : 'text-stone-300 dark:text-stone-600'" 
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                    @endforeach
                    <input type="hidden" name="rating" x-model="rating" required>
                </div>

                <textarea name="comment" placeholder="Any thoughts on your drink? (Optional)" class="w-full bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-700 rounded-xl text-sm focus:ring-amber-500 focus:border-amber-500 mb-4 p-3 outline-none transition-all dark:text-white"></textarea>
                
                <button type="submit" class="w-full sm:w-auto bg-stone-900 dark:bg-amber-600 text-white px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg transition transform hover:-translate-y-0.5 active:scale-95">
                    Submit Review for {{ $item->product->name }}
                </button>
            </form>
        </div>
        @endforeach
    </div>
@endforeach