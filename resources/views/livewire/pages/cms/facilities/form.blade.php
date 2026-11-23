<?php

use App\Models\Cms\{FacilityItem, Media};
use App\Support\CmsMediaUploader;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    use WithFileUploads;

    public ?FacilityItem $facility = null;
    public string $title = '';
    public string $description = '';
    public string $category = 'general';
    public int $sort_order = 0;
    public bool $is_published = true;
    public $image;

    public function mount(?FacilityItem $facility = null): void
    {
        $this->facility = $facility;
        if ($facility?->exists) {
            $this->fill($facility->only(['title', 'description', 'category', 'sort_order', 'is_published']));
        }
    }

    public function save(CmsMediaUploader $uploader): void
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'in:general,classrooms,boarding,sports,technology,transport'],
            'sort_order' => ['integer'],
            'is_published' => ['boolean'],
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
        ]);

        unset($data['image']);
        $old = $this->facility?->image_path;

        if ($this->image) {
            $path = $uploader->upload($this->image, 'facilities');
            $data['image_path'] = $path;
            Media::create([
                'name' => $this->image->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $this->image->getMimeType(),
                'size' => $this->image->getSize(),
                'folder' => 'facilities',
            ]);
        }

        $item = $this->facility?->exists
            ? tap($this->facility)->update($data)
            : FacilityItem::create($data);

        if ($old && isset($data['image_path'])) {
            $uploader->deleteIfUnreferenced($old);
        }

        session()->flash('success', 'Facility saved.');
        $this->redirect(route('cms.facilities.edit', $item), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold">{{ $facility?->exists ? 'Edit' : 'New' }} Facility</h1>
        <a href="{{ route('cms.facilities.index') }}" wire:navigate class="text-sm text-indigo-600">Back</a>
    </div>

    @if (session('success'))
        <div class="rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
    @endif

    <form wire:submit="save" class="space-y-5 rounded-lg bg-white p-6 shadow">
        <div>
            <x-input-label for="title" value="Title" />
            <x-text-input id="title" wire:model="title" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="description" value="Description" />
            <textarea id="description" wire:model="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300"></textarea>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <x-input-label for="category" value="Category" />
                <select id="category" wire:model="category" class="mt-1 block w-full rounded-md border-gray-300">
                    @foreach (['general', 'classrooms', 'boarding', 'sports', 'technology', 'transport'] as $value)
                        <option value="{{ $value }}">{{ str($value)->title() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="sort_order" value="Sort order" />
                <x-text-input id="sort_order" type="number" wire:model="sort_order" class="mt-1 block w-full" />
            </div>
        </div>

        <x-cms.image-field label="Image" :current="$facility?->image_path" model="image" />

        <label class="flex gap-2">
            <input type="checkbox" wire:model="is_published" class="rounded"> Published
        </label>

        <x-primary-button wire:loading.attr="disabled">Save facility</x-primary-button>
    </form>
</div>
