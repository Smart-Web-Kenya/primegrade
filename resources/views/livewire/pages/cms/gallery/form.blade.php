<?php

use App\Models\Cms\{GalleryImage, Media};
use App\Support\CmsMediaUploader;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    use WithFileUploads;

    public ?GalleryImage $galleryImage = null;
    public string $title = '';
    public string $caption = '';
    public string $alt_text = '';
    public int $sort_order = 0;
    public bool $is_published = true;
    public $image;

    public function mount(?GalleryImage $galleryImage = null): void
    {
        $this->galleryImage = $galleryImage;
        if ($galleryImage?->exists) {
            $this->fill($galleryImage->only(['title', 'caption', 'alt_text', 'sort_order', 'is_published']));
        }
    }

    public function save(CmsMediaUploader $uploader): void
    {
        $data = $this->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['integer'],
            'is_published' => ['boolean'],
            'image' => [$this->galleryImage?->exists ? 'nullable' : 'required', 'file', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
        ]);

        unset($data['image']);
        $old = $this->galleryImage?->image_path;

        if ($this->image) {
            $path = $uploader->upload($this->image, 'gallery');
            $data['image_path'] = $path;
            Media::create([
                'name' => $this->image->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $this->image->getMimeType(),
                'size' => $this->image->getSize(),
                'alt_text' => $this->alt_text,
                'folder' => 'gallery',
            ]);
        }

        $item = $this->galleryImage?->exists
            ? tap($this->galleryImage)->update($data)
            : GalleryImage::create($data);

        if ($old && isset($data['image_path'])) {
            $uploader->deleteIfUnreferenced($old);
        }

        session()->flash('success', 'Gallery image saved.');
        $this->redirect(route('cms.gallery.edit', $item), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold">{{ $galleryImage?->exists ? 'Edit' : 'Add' }} Gallery Image</h1>
        <a href="{{ route('cms.gallery.index') }}" wire:navigate class="text-sm text-indigo-600">Back</a>
    </div>

    @if (session('success'))
        <div class="rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
    @endif

    <form wire:submit="save" class="grid gap-5 rounded-lg bg-white p-6 shadow sm:grid-cols-2">
        @foreach (['title' => 'Title', 'caption' => 'Caption', 'alt_text' => 'Alt text'] as $field => $label)
            <div class="{{ $field === 'caption' ? 'sm:col-span-2' : '' }}">
                <x-input-label :for="$field" :value="$label" />
                <x-text-input :id="$field" wire:model="{{ $field }}" class="mt-1 block w-full" />
            </div>
        @endforeach

        <div>
            <x-input-label for="sort_order" value="Sort order" />
            <x-text-input id="sort_order" type="number" wire:model="sort_order" class="mt-1 block w-full" />
        </div>

        <label class="flex items-end gap-2 pb-2">
            <input type="checkbox" wire:model="is_published" class="rounded"> Published
        </label>

        <div class="sm:col-span-2">
            <x-cms.image-field
                :label="$galleryImage?->exists ? 'Replacement image' : 'Image'"
                :current="$galleryImage?->image_path"
                model="image"
            />
        </div>

        <div class="sm:col-span-2">
            <x-primary-button wire:loading.attr="disabled">Save image</x-primary-button>
        </div>
    </form>
</div>
