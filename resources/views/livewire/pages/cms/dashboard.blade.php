<?php

use App\Models\Cms\{Announcement, FacilityItem, FormSubmission, Media, Page, Program};
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public function with(): array
    {
        return [
            'stats' => [
                'Pages' => Page::count(),
                'Published announcements' => Announcement::where('is_published', true)->count(),
                'Unread form submissions' => FormSubmission::unread()->count(),
                'Media files' => Media::count(),
                'Programs' => Program::count(),
                'Facilities' => FacilityItem::count(),
            ],
            'recent' => collect()
                ->merge(Page::latest('updated_at')->limit(5)->get()->map(fn ($item) => ['type' => 'Page', 'title' => $item->title, 'at' => $item->updated_at, 'url' => route('cms.pages.edit', $item)]))
                ->merge(Announcement::latest('updated_at')->limit(5)->get()->map(fn ($item) => ['type' => 'Announcement', 'title' => $item->title, 'at' => $item->updated_at, 'url' => route('cms.announcements.edit', $item)]))
                ->merge(FormSubmission::latest()->limit(5)->get()->map(fn ($item) => ['type' => $item->typeLabel(), 'title' => $item->name, 'at' => $item->created_at, 'url' => route('cms.submissions.show', $item)]))
                ->sortByDesc('at')->take(5),
        ];
    }
}; ?>

<div class="space-y-8">
    <div><h1 class="text-2xl font-semibold text-gray-900">CMS Dashboard</h1><p class="text-sm text-gray-500">Manage the school website content.</p></div>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($stats as $label => $value)
            <div class="rounded-lg bg-white p-5 shadow"><p class="text-sm text-gray-500">{{ $label }}</p><p class="mt-1 text-3xl font-semibold">{{ $value }}</p></div>
        @endforeach
    </div>
    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-lg bg-white p-5 shadow"><h2 class="mb-4 font-semibold">Recently updated</h2>
            <div class="divide-y">@forelse ($recent as $item)<a class="flex justify-between py-3 text-sm hover:text-indigo-600" href="{{ $item['url'] }}" wire:navigate><span><span class="text-xs text-gray-400">{{ $item['type'] }}</span> {{ $item['title'] }}</span><span class="text-gray-400">{{ $item['at']->diffForHumans() }}</span></a>@empty<p class="text-sm text-gray-500">No content yet.</p>@endforelse</div>
        </section>
        <section class="rounded-lg bg-white p-5 shadow"><h2 class="mb-4 font-semibold">Quick links</h2><div class="grid gap-3 sm:grid-cols-2">
            <a class="rounded border p-3 text-sm hover:border-indigo-500" href="{{ route('cms.submissions.index') }}" wire:navigate>Form submissions</a>
            <a class="rounded border p-3 text-sm hover:border-indigo-500" href="{{ route('cms.announcements.create') }}" wire:navigate>New announcement</a>
            <a class="rounded border p-3 text-sm hover:border-indigo-500" href="{{ route('cms.programs.create') }}" wire:navigate>New program</a>
            <a class="rounded border p-3 text-sm hover:border-indigo-500" href="{{ route('cms.settings') }}" wire:navigate>Site settings</a>
        </div></section>
    </div>
</div>

