<form action="{{ route('barista.update_status', $order->id) }}" method="POST" class="w-full">
    @csrf
    <input type="hidden" name="status" value="preparing">
    <button type="submit" class="w-full bg-amber-500 text-white py-2 rounded-lg hover:bg-amber-600 transition">
        Start Making
    </button>
</form>