<?php

use App\Models\Cms\Announcement;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public ?Announcement $announcement = null;
    public string $title = '', $slug = '', $category = '', $excerpt = '', $body = '', $cta_text = '', $cta_url = '';
    public ?string $published_at = null;
    public int $sort_order = 0;
    public bool $is_published = true;

    public function mount(?Announcement $announcement = null): void
    {
        $this->announcement = $announcement;
        if ($announcement?->exists) {
            $this->fill($announcement->only(['title','slug','category','excerpt','body','cta_text','cta_url','sort_order','is_published']));
            $this->published_at = $announcement->published_at?->format('Y-m-d\TH:i');
        }
    }

    public function updatedTitle(): void
    {
        if (! $this->announcement?->exists || $this->slug === '') $this->slug = Str::slug($this->title);
    }

    public function save(): void
    {
        $data = $this->validate([
            'title' => ['required','string','max:255'], 'slug' => ['nullable','string','max:255', Rule::unique('cms_announcements','slug')->ignore($this->announcement?->id)],
            'category' => ['nullable','string','max:255'], 'excerpt' => ['nullable','string'], 'body' => ['nullable','string'],
            'cta_text' => ['nullable','string','max:255'], 'cta_url' => ['nullable','string','max:255'],
            'published_at' => ['nullable','date'], 'sort_order' => ['integer'], 'is_published' => ['boolean'],
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $item = $this->announcement?->exists ? tap($this->announcement)->update($data) : Announcement::create($data);
        session()->flash('success', 'Announcement saved.');
        $this->redirect(route('cms.announcements.edit', $item), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-4xl space-y-6"><div class="flex justify-between"><h1 class="text-2xl font-semibold">{{ $announcement?->exists ? 'Edit' : 'New' }} Announcement</h1><a href="{{ route('cms.announcements.index') }}" wire:navigate class="text-sm text-indigo-600">Back</a></div>
@if(session('success'))<div class="rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>@endif
<form wire:submit="save" class="grid gap-5 rounded-lg bg-white p-6 shadow sm:grid-cols-2">
    @foreach(['title'=>'Title','slug'=>'Slug','category'=>'Category','cta_text'=>'CTA text','cta_url'=>'CTA URL'] as $field=>$label)<div class="{{ in_array($field,['title','slug']) ? 'sm:col-span-2' : '' }}"><x-input-label :for="$field" :value="$label"/><x-text-input :id="$field" wire:model.blur="{{ $field }}" class="mt-1 block w-full"/><x-input-error :messages="$errors->get($field)" class="mt-2"/></div>@endforeach
    <div class="sm:col-span-2"><x-input-label for="excerpt" value="Excerpt"/><textarea id="excerpt" wire:model="excerpt" rows="3" class="mt-1 block w-full rounded-md border-gray-300"></textarea></div>
    <div class="sm:col-span-2"><x-input-label for="body" value="Body"/><textarea id="body" wire:model="body" rows="8" class="mt-1 block w-full rounded-md border-gray-300"></textarea></div>
    <div><x-input-label for="published_at" value="Publish date"/><x-text-input id="published_at" type="datetime-local" wire:model="published_at" class="mt-1 block w-full"/></div>
    <div><x-input-label for="sort_order" value="Sort order"/><x-text-input id="sort_order" type="number" wire:model="sort_order" class="mt-1 block w-full"/></div>
    <label class="flex items-center gap-2 sm:col-span-2"><input type="checkbox" wire:model="is_published" class="rounded"><span>Published</span></label><div class="sm:col-span-2"><x-primary-button>Save announcement</x-primary-button></div>
</form></div>
