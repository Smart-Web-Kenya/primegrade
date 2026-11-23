<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Account</p>
            <h1 class="text-sm font-semibold text-slate-900 sm:text-base">{{ __('Profile') }}</h1>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-lg bg-white p-4 shadow sm:p-8">
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>

        <div class="rounded-lg bg-white p-4 shadow sm:p-8">
            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>
        </div>

        <div class="rounded-lg bg-white p-4 shadow sm:p-8">
            <div class="max-w-xl">
                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>
</x-app-layout>
