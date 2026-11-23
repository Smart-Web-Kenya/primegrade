<?php

use App\Models\Cms\Announcement;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public string $search = '';
    public ?int $deleting = null;

    public function delete(): void
    {
        Announcement::findOrFail($this->deleting)->delete();
        $this->deleting = null;
        $this->dispatch('close-modal', 'delete-announcement');
    }

    public function with(): array
    {
        return ['announcements' => Announcement::query()->when($this->search, fn ($q) => $q->where(fn ($q) => $q->where('title', 'like', "%{$this->search}%")->orWhere('category', 'like', "%{$this->search}%")))->latest()->get()];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between"><div><h1 class="text-2xl font-semibold">Announcements</h1></div><a href="{{ route('cms.announcements.create') }}" wire:navigate class="rounded bg-indigo-600 px-4 py-2 text-sm text-white">New announcement</a></div>
    <x-text-input wire:model.live.debounce.300ms="search" placeholder="Search announcements…" class="w-full max-w-md" />
    <div class="overflow-hidden rounded-lg bg-white shadow"><table class="min-w-full divide-y"><thead class="bg-gray-50"><tr><th class="px-5 py-3 text-left text-xs uppercase text-gray-500">Title</th><th class="px-5 py-3 text-left text-xs uppercase text-gray-500">Category</th><th class="px-5 py-3">Status</th><th></th></tr></thead><tbody class="divide-y">
        @forelse($announcements as $item)<tr><td class="px-5 py-4 font-medium">{{ $item->title }}</td><td class="px-5 py-4 text-sm">{{ $item->category ?: '—' }}</td><td class="px-5 py-4 text-center"><span class="rounded-full px-2 py-1 text-xs {{ $item->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100' }}">{{ $item->is_published ? 'Published' : 'Draft' }}</span></td><td class="space-x-3 px-5 py-4 text-right"><a href="{{ route('cms.announcements.edit', $item) }}" wire:navigate class="text-sm text-indigo-600">Edit</a><button wire:click="$set('deleting', {{ $item->id }})" x-on:click="$dispatch('open-modal', 'delete-announcement')" class="text-sm text-red-600">Delete</button></td></tr>
        @empty<tr><td colspan="4" class="p-8 text-center text-gray-500">No announcements found.</td></tr>@endforelse
    </tbody></table></div>
    <x-modal name="delete-announcement" focusable><div class="p-6"><h2 class="text-lg font-semibold">Delete announcement?</h2><p class="mt-2 text-sm text-gray-600">This action cannot be undone.</p><div class="mt-6 flex justify-end gap-3"><x-secondary-button x-on:click="$dispatch('close-modal', 'delete-announcement')">Cancel</x-secondary-button><x-danger-button wire:click="delete">Delete</x-danger-button></div></div></x-modal>
</div>
