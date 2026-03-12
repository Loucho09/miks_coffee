<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - Mik's Coffee Shop</title>
    <link href="https://fonts.bunny.net/css?family=outfit:400,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-950 text-stone-200 font-sans min-h-screen flex items-center justify-center p-6 text-center">
    <div class="max-w-md">
        <div class="w-24 h-24 bg-stone-900 rounded-[2rem] border border-amber-500/20 flex items-center justify-center mx-auto mb-8 shadow-2xl">
            <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3" />
            </svg>
        </div>
        <h1 class="text-4xl font-black uppercase tracking-tighter italic mb-4">Signal Lost.</h1>
        <p class="text-stone-400 font-light leading-relaxed mb-10">We couldn't reach the brewery. Please check your connection to browse our menu and claim your stars.</p>
        <button onclick="window.location.reload()" class="px-10 py-4 bg-amber-600 rounded-full font-black uppercase text-xs tracking-widest text-white shadow-xl hover:bg-amber-700 transition-all active:scale-95">Retry Connection</button>
    </div>
</body>
</html>