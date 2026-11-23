@props([
    'label',
    'items',
    'addMethod',
    'removeMethod',
    'modelPrefix',
    'placeholder' => 'Enter a value',
])

<div {{ $attributes->class('space-y-3') }}>
    <div class="flex items-center justify-between gap-3">
        <x-input-label :value="$label" />
        <button type="button" wire:click="{{ $addMethod }}" class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200">
            Add item
        </button>
    </div>

    <div class="space-y-2">
        @forelse ($items as $index => $item)
            <div class="flex gap-2" wire:key="{{ $modelPrefix }}-{{ $index }}">
                <x-text-input
                    wire:model="{{ $modelPrefix }}.{{ $index }}"
                    class="block w-full"
                    :placeholder="$placeholder"
                />
                <button
                    type="button"
                    wire:click="{{ $removeMethod }}({{ $index }})"
                    class="shrink-0 rounded-md px-3 text-sm text-red-600 hover:bg-red-50"
                >Remove</button>
            </div>
        @empty
            <p class="rounded-md border border-dashed border-gray-300 px-3 py-4 text-sm text-gray-500">No items yet. Click “Add item”.</p>
        @endforelse
    </div>

    <x-input-error :messages="$errors->get($modelPrefix)" class="mt-1" />
</div>
