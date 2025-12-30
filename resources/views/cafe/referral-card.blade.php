<div class="bg-coffee-800 text-white p-6 rounded-[2rem] shadow-xl relative overflow-hidden">
    <div class="relative z-10">
        <h3 class="text-xl font-bold italic uppercase tracking-tighter mb-2">Invite your coffee crew</h3>
        <p class="text-coffee-200 text-sm mb-4">Share your link. When they order, you both get 50 points!</p>
        
        <div class="flex gap-2">
            <input type="text" readonly value="{{ route('register', ['ref' => Auth::user()->referral_code]) }}" 
                   class="bg-white/10 border-white/20 text-white rounded-lg text-xs w-full focus:ring-amber-500" id="refLink">
            <button onclick="copyRef()" class="bg-amber-500 text-stone-900 font-black px-4 py-2 rounded-lg text-xs uppercase">Copy</button>
        </div>
    </div>
</div>

<script>
function copyRef() {
    var copyText = document.getElementById("refLink");
    copyText.select();
    navigator.clipboard.writeText(copyText.value);
    alert("Link copied to clipboard!");
}
</script>