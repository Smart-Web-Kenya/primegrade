@props([
    'label' => 'Image',
    'current' => null,
    'model',
    'hint' => null,
])

@php
    $preview = null;
    if ($current) {
        $preview = str_starts_with((string) $current, 'http') ? $current : asset(ltrim((string) $current, '/'));
    }
@endphp

<div {{ $attributes->class('space-y-2') }}>
    <x-input-label :value="$label" />

    @if ($preview)
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
            <img src="{{ $preview }}" alt="" class="max-h-48 w-full object-cover">
            <p class="truncate px-3 py-2 text-xs text-gray-500">{{ $current }}</p>
        </div>
    @endif

    <input
        type="file"
        wire:model="{{ $model }}"
        accept="image/*,.svg"
        class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-md file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100"
    />

    @if ($hint)
        <p class="text-xs text-gray-500">{{ $hint }}</p>
    @endif

    <div wire:loading wire:target="{{ $model }}" class="text-xs text-indigo-600">Uploading…</div>
    <x-input-error :messages="$errors->get($model)" class="mt-1" />
</div>
