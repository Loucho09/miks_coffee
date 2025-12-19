<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-stone-50 dark:bg-stone-950 px-4">
        
        <div class="w-full max-w-md bg-white dark:bg-stone-900 rounded-3xl shadow-2xl border border-stone-100 dark:border-stone-800 overflow-hidden">
            <div class="p-10 text-center">
                <div class="w-24 h-24 bg-green-50 dark:bg-green-900/20 text-green-600 rounded-full flex items-center justify-center mx-auto mb-8 border border-green-100 dark:border-green-800/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>

                <h2 class="text-3xl font-bold text-stone-900 dark:text-white mb-4">Verify Your Email</h2>
                
                <p class="text-stone-500 dark:text-stone-400 mb-8 text-sm leading-relaxed">
                    {{ __('Thanks for signing up! Please verify your email by clicking the link we just sent to you. If you didn\'t receive it, we\'ll happily send another.') }}
                </p>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-8 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 text-sm font-semibold p-4 rounded-xl border border-amber-200 dark:border-amber-800/30">
                        {{ __('A new verification link has been sent!') }}
                    </div>
                @endif

                <div class="space-y-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full bg-amber-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-amber-700 transition transform hover:-translate-y-0.5">
                            {{ __('Resend Email') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-stone-400 hover:text-stone-800 dark:hover:text-stone-200 font-bold underline transition">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>