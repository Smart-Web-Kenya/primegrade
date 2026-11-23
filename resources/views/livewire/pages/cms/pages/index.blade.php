<?php

use App\Models\Cms\Page;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public function with(): array
    {
        return ['pages' => Page::orderBy('title')->get()];
    }
}; ?>

<div class="space-y-6">
    <div><h1 class="text-2xl font-semibold">Pages</h1><p class="text-sm text-gray-500">Edit the seeded public pages and their structured content.</p></div>
    <div class="overflow-hidden rounded-lg bg-white shadow"><table class="min-w-full divide-y">
        <thead class="bg-gray-50"><tr><th class="px-5 py-3 text-left text-xs uppercase text-gray-500">Title</th><th class="px-5 py-3 text-left text-xs uppercase text-gray-500">Slug</th><th class="px-5 py-3 text-left text-xs uppercase text-gray-500">Status</th><th></th></tr></thead>
        <tbody class="divide-y">@forelse ($pages as $page)<tr><td class="px-5 py-4 font-medium">{{ $page->title }}</td><td class="px-5 py-4 text-sm text-gray-500">{{ $page->slug }}</td><td class="px-5 py-4"><span class="rounded-full px-2 py-1 text-xs {{ $page->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $page->is_published ? 'Published' : 'Draft' }}</span></td><td class="px-5 py-4 text-right"><a class="text-sm text-indigo-600" href="{{ route('cms.pages.edit', $page) }}" wire:navigate>Edit</a></td></tr>@empty<tr><td colspan="4" class="p-8 text-center text-gray-500">No pages found.</td></tr>@endforelse</tbody>
    </table></div>
</div>
