<section>
    <header class="mb-10 flex items-start gap-5">
        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600 shrink-0 border border-amber-500/20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div>
            <h2 class="text-xl md:text-2xl font-black text-stone-900 dark:text-stone-100 uppercase tracking-tighter italic leading-none">
                {{ __('Personal Information') }}
            </h2>
            <p class="mt-2 text-[10px] text-stone-400 font-black uppercase tracking-[0.2em]">
                {{ __("Public identity and roast notification channels") }}
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
        @csrf
        @method('patch')

        <div class="relative group">
            <label for="name" class="text-[10px] font-black uppercase tracking-widest text-stone-400 mb-2 block ml-1">{{ __('Full Name') }}</label>
            <div class="relative">
                <input id="name" name="name" type="text" 
                    class="block w-full border-2 border-stone-100 dark:border-stone-800 bg-stone-50 dark:bg-stone-950 text-stone-900 dark:text-stone-100 focus:border-amber-500 focus:ring-0 rounded-2xl px-6 py-4 text-sm font-bold shadow-inner transition-all group-hover:border-stone-200 dark:group-hover:border-stone-700" 
                    value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                <div class="absolute right-6 top-1/2 -translate-y-1/2 text-stone-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>
            <x-input-error class="mt-2 text-[10px]" :messages="$errors->get('name')" />
        </div>

        <div class="relative group">
            <label for="email" class="text-[10px] font-black uppercase tracking-widest text-stone-400 mb-2 block ml-1">{{ __('Email Address') }}</label>
            <div class="relative">
                <input id="email" name="email" type="email" 
                    class="block w-full border-2 border-stone-100 dark:border-stone-800 bg-stone-50 dark:bg-stone-950 text-stone-900 dark:text-stone-100 focus:border-amber-500 focus:ring-0 rounded-2xl px-6 py-4 text-sm font-bold shadow-inner transition-all group-hover:border-stone-200 dark:group-hover:border-stone-700" 
                    value="{{ old('email', $user->email) }}" required autocomplete="username" />
                <div class="absolute right-6 top-1/2 -translate-y-1/2 text-stone-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <x-input-error class="mt-2 text-[10px]" :messages="$errors->get('email')" />
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-6 pt-6 border-t border-stone-100 dark:border-stone-800">
            <button type="submit" class="w-full sm:w-auto bg-stone-900 dark:bg-amber-600 hover:bg-amber-600 dark:hover:bg-amber-500 text-white font-black uppercase tracking-[0.2em] text-[10px] py-5 px-12 rounded-2xl shadow-xl transition transform hover:-translate-y-1 active:scale-95 shadow-amber-600/10">
                {{ __('Update Profile') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="flex items-center gap-2 text-green-600 dark:text-green-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <p class="text-[10px] font-black uppercase tracking-widest">
                        {{ __('Success: Changes Injected') }}
                    </p>
                </div>
            @endif
        </div>
    </form>
</section>