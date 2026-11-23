<?php
use App\Models\Cms\GalleryImage;
use App\Support\CmsMediaUploader;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
new #[Layout('layouts.app')] class extends Component {
    public function delete(int $id,CmsMediaUploader $uploader): void {$item=GalleryImage::findOrFail($id);$path=$item->image_path;$item->delete();$uploader->deleteIfUnreferenced($path);}
    public function with(): array{return ['images'=>GalleryImage::orderBy('sort_order')->get(),'uploader'=>app(CmsMediaUploader::class)];}
}; ?>
<div class="space-y-6"><div class="flex justify-between"><h1 class="text-2xl font-semibold">Gallery</h1><a href="{{ route('cms.gallery.create') }}" wire:navigate class="rounded bg-indigo-600 px-4 py-2 text-sm text-white">Add image</a></div>
<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">@forelse($images as $item)<article class="overflow-hidden rounded-lg bg-white shadow"><img src="{{ $uploader->url($item->image_path) }}" alt="{{ $item->alt_text }}" class="h-48 w-full object-cover"><div class="p-4"><div class="flex justify-between"><h2 class="font-medium">{{ $item->title?:'Untitled' }}</h2><span class="text-xs text-gray-500">#{{ $item->sort_order }}</span></div><p class="mt-1 text-sm text-gray-500">{{ $item->caption }}</p><div class="mt-4 flex justify-end gap-3"><a href="{{ route('cms.gallery.edit',$item) }}" wire:navigate class="text-sm text-indigo-600">Edit</a><button wire:click="delete({{ $item->id }})" wire:confirm="Delete this image?" class="text-sm text-red-600">Delete</button></div></div></article>@empty<p class="text-gray-500">No gallery images.</p>@endforelse</div></div>
