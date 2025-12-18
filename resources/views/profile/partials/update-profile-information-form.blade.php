<section>
    <header class="mb-8">
        <h2 class="text-xl font-bold text-stone-900 dark:text-stone-100">
            {{ __('Personal Information') }}
        </h2>
        <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">
            {{ __("Manage your public name and contact email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-stone-700 dark:text-stone-300 font-bold" />
            <input id="name" name="name" type="text" 
                class="mt-1.5 block w-full border-stone-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm" 
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-stone-700 dark:text-stone-300 font-bold" />
            <input id="email" name="email" type="email" 
                class="mt-1.5 block w-full border-stone-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm" 
                value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition transform hover:-translate-y-0.5 active:scale-95">
                {{ __('Update Profile') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 dark:text-green-400 font-medium">
                    {{ __('Saved successfully.') }}
                </p>
            @endif
        </div>
    </form>
</section>