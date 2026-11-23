<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new
#[Layout('layouts.app')]
#[Title('Dashboard')]
class extends Component {
}; ?>

<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}.</p>
    </div>

    <div class="rounded-lg bg-white p-6 shadow">
        <p class="text-gray-700">You're logged in to the {{ config('app.name') }} admin area.</p>
        <a
            href="{{ route('cms.dashboard') }}"
            wire:navigate
            class="mt-4 inline-flex text-sm font-medium text-indigo-600 hover:text-indigo-800"
        >
            Open CMS →
        </a>
    </div>
</div>
