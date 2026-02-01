<section>
    <header class="mb-8">
        <h2 class="text-xl font-bold text-stone-900 dark:text-stone-500">
            {{ __('Security') }}
        </h2>
        <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6 bg-slate-400/5 p-6 rounded-2xl border border-stone-300 dark:border-stone-700 shadow-inner">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" :value="__('Current Password')" class="text-stone-700 dark:text-stone-300 font-bold" />
            <input id="current_password" name="current_password" type="password" 
                class="mt-1.5 block w-full border-stone-300 dark:border-stone-700 bg-stone-100  dark:bg-stone-800 text-stone-900 dark:text-stone-100 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm" 
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="password" :value="__('New Password')" class="text-stone-700 dark:text-stone-300 font-bold" />
                <input id="password" name="password" type="password" 
                    class="mt-1.5 block w-full border-stone-300 dark:border-stone-700 bg-stone-100  dark:bg-stone-800 text-stone-900 dark:text-stone-100 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm" 
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="text-stone-700 dark:text-stone-300 font-bold" />
                <input id="password_confirmation" name="password_confirmation" type="password" 
                    class="mt-1.5 block w-full border-stone-300 dark:border-stone-700 bg-stone-100  dark:bg-stone-800 text-stone-900 dark:text-stone-100 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm" 
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-stone-800 dark:bg-stone-700 hover:bg-stone-900 dark:hover:bg-stone-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition transform hover:-translate-y-0.5 active:scale-95">
                {{ __('Change Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 dark:text-green-400 font-medium">
                    {{ __('Password updated.') }}
                </p>
            @endif
        </div>
    </form>
</section>