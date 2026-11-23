<?php

use App\Models\Cms\Media;
use App\Models\Cms\Page;
use App\Support\CmsMediaUploader;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    use WithFileUploads;

    public Page $page;
    public string $title = '';
    public ?string $excerpt = null;
    public ?string $seo_title = null;
    public ?string $seo_description = null;
    public ?string $seo_keywords = null;
    public ?string $og_title = null;
    public ?string $og_description = null;
    public ?string $og_image = null;
    public bool $is_published = true;
    public array $content = [];
    public array $uploads = [];
    public $ogImageUpload = null;

    public function mount(Page $page): void
    {
        $this->page = $page;
        $this->fill($page->only([
            'title', 'excerpt', 'seo_title', 'seo_description', 'seo_keywords',
            'og_title', 'og_description', 'og_image', 'is_published',
        ]));
        $this->content = $this->normalizeContent($page->content ?? []);
    }

    public function addListItem(string $section): void
    {
        if (! isset($this->content[$section]) || ! is_array($this->content[$section])) {
            $this->content[$section] = [];
        }

        $sample = collect($this->content[$section])->first(fn ($item) => is_array($item));
        $item = [];

        if (is_array($sample) && $sample !== []) {
            foreach (array_keys($sample) as $field) {
                $item[$field] = '';
            }
        } else {
            $item = ['title' => '', 'body' => ''];
        }

        $this->content[$section][] = $item;
    }

    public function removeListItem(string $section, int $index): void
    {
        if (! isset($this->content[$section][$index])) {
            return;
        }

        unset($this->content[$section][$index]);
        $this->content[$section] = array_values($this->content[$section]);
    }

    public function addSectionField(string $section): void
    {
        if (! isset($this->content[$section]) || ! is_array($this->content[$section]) || array_is_list($this->content[$section])) {
            return;
        }

        $key = 'new_field_'.(count($this->content[$section]) + 1);
        $this->content[$section][$key] = '';
    }

    public function save(CmsMediaUploader $uploader): void
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:255'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'is_published' => ['boolean'],
            'content' => ['array'],
            'ogImageUpload' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            'uploads' => ['nullable', 'array'],
        ]);

        $content = $this->content;
        $this->applyUploads($content, $this->uploads, $uploader);

        if ($this->ogImageUpload instanceof TemporaryUploadedFile) {
            $path = $uploader->upload($this->ogImageUpload, 'pages');
            Media::create([
                'name' => $this->ogImageUpload->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $this->ogImageUpload->getMimeType(),
                'size' => $this->ogImageUpload->getSize(),
                'folder' => 'pages',
            ]);
            $old = $this->og_image;
            $data['og_image'] = $path;
            if (is_string($old) && $old !== '') {
                $uploader->deleteIfUnreferenced($old);
            }
            $this->ogImageUpload = null;
        }

        $data['content'] = $this->cleanContent($content);
        unset($data['ogImageUpload'], $data['uploads']);

        $this->page->update($data);
        $this->content = $data['content'];
        $this->og_image = $this->page->og_image;
        $this->uploads = [];

        session()->flash('success', 'Page saved.');
    }

    public function isImageField(string $key, mixed $value = null): bool
    {
        $key = strtolower($key);

        if (str_contains($key, 'image') || str_ends_with($key, '_img') || $key === 'photo' || $key === 'logo') {
            return true;
        }

        if (! is_string($value) || $value === '') {
            return false;
        }

        return (bool) preg_match('/\.(jpe?g|png|gif|webp|svg)$/i', $value)
            || str_starts_with($value, 'uploads/');
    }

    public function isListSection(mixed $value): bool
    {
        return is_array($value) && array_is_list($value);
    }

    public function isAssocSection(mixed $value): bool
    {
        return is_array($value) && $value !== [] && ! array_is_list($value);
    }

    private function applyUploads(array &$content, array $uploads, CmsMediaUploader $uploader, string $prefix = ''): void
    {
        foreach ($uploads as $key => $value) {
            $pathKey = $prefix === '' ? (string) $key : "{$prefix}.{$key}";

            if ($value instanceof TemporaryUploadedFile) {
                $path = $uploader->upload($value, 'pages');
                Media::create([
                    'name' => $value->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $value->getMimeType(),
                    'size' => $value->getSize(),
                    'folder' => 'pages',
                ]);

                $old = data_get($content, $pathKey);
                data_set($content, $pathKey, $path);

                if (is_string($old) && $old !== '' && $old !== $path) {
                    $uploader->deleteIfUnreferenced($old);
                }

                continue;
            }

            if (is_array($value)) {
                $this->applyUploads($content, $value, $uploader, $pathKey);
            }
        }
    }

    private function normalizeContent(array $content): array
    {
        foreach ($content as $key => $value) {
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $content[$key] = $decoded;
                }
            }
        }

        return $content;
    }

    private function cleanContent(array $content): array
    {
        foreach ($content as $key => $value) {
            if (is_array($value)) {
                if (array_is_list($value)) {
                    $content[$key] = array_values(array_map(function ($item) {
                        if (! is_array($item)) {
                            return $item;
                        }

                        return collect($item)->map(fn ($v) => is_string($v) ? $v : (is_scalar($v) || $v === null ? (string) $v : $v))->all();
                    }, $value));
                } else {
                    $content[$key] = $this->cleanContent($value);
                }
            }
        }

        return $content;
    }
}; ?>

<div class="mx-auto max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Edit {{ $page->title }}</h1>
            <p class="text-sm text-gray-500">/{{ $page->slug }}</p>
        </div>
        <a href="{{ route('cms.pages.index') }}" class="text-sm text-indigo-600" wire:navigate>Back</a>
    </div>

    @if (session('success'))
        <div class="rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
    @endif

    <form wire:submit="save" class="space-y-6 rounded-lg bg-white p-6 shadow">
        <div>
            <x-input-label for="title" value="Title" />
            <x-text-input id="title" wire:model="title" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="excerpt" value="Excerpt" />
            <textarea id="excerpt" wire:model="excerpt" rows="3" class="mt-1 block w-full rounded-md border-gray-300"></textarea>
        </div>

        @if ($content)
            <fieldset class="space-y-6 border-t pt-5">
                <legend class="mb-1 font-semibold text-gray-900">Page content</legend>
                <p class="text-sm text-gray-500">Edit each section with the fields below. Lists support add and remove.</p>

                @foreach ($content as $sectionKey => $sectionValue)
                    <div class="rounded-lg border border-gray-200 p-4" wire:key="section-{{ $sectionKey }}">
                        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">
                            {{ str($sectionKey)->replace('_', ' ')->title() }}
                        </h3>

                        @if ($this->isListSection($sectionValue))
                            <div class="space-y-4">
                                @forelse ($sectionValue as $index => $item)
                                    <div class="rounded-md border border-dashed border-gray-300 p-4" wire:key="list-{{ $sectionKey }}-{{ $index }}">
                                        <div class="mb-3 flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-700">Item {{ $index + 1 }}</p>
                                            <button type="button" wire:click="removeListItem('{{ $sectionKey }}', {{ $index }})" class="text-sm text-red-600 hover:text-red-800">Remove</button>
                                        </div>
                                        <div class="grid gap-4">
                                            @if (is_array($item))
                                                @foreach ($item as $field => $value)
                                                    <div>
                                                        @if ($this->isImageField($field, $value))
                                                            <x-cms.image-field
                                                                :label="str($field)->replace('_', ' ')->title()"
                                                                :current="is_string($value) ? $value : null"
                                                                :model="'uploads.'.$sectionKey.'.'.$index.'.'.$field"
                                                                hint="Upload replaces this item image on save. Or edit the path below."
                                                            />
                                                            <x-text-input wire:model="content.{{ $sectionKey }}.{{ $index }}.{{ $field }}" class="mt-2 block w-full" placeholder="uploads/cms/..." />
                                                        @elseif (is_string($value) && strlen($value) > 80)
                                                            <x-input-label :for="'content-'.$sectionKey.'-'.$index.'-'.$field" :value="str($field)->replace('_', ' ')->title()" />
                                                            <textarea id="content-{{ $sectionKey }}-{{ $index }}-{{ $field }}" wire:model="content.{{ $sectionKey }}.{{ $index }}.{{ $field }}" rows="4" class="mt-1 block w-full rounded-md border-gray-300"></textarea>
                                                        @else
                                                            <x-input-label :for="'content-'.$sectionKey.'-'.$index.'-'.$field" :value="str($field)->replace('_', ' ')->title()" />
                                                            <x-text-input id="content-{{ $sectionKey }}-{{ $index }}-{{ $field }}" wire:model="content.{{ $sectionKey }}.{{ $index }}.{{ $field }}" class="mt-1 block w-full" />
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <x-text-input wire:model="content.{{ $sectionKey }}.{{ $index }}" class="mt-1 block w-full" />
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="rounded-md border border-dashed border-gray-300 px-3 py-4 text-sm text-gray-500">No items in this list yet.</p>
                                @endforelse

                                <button type="button" wire:click="addListItem('{{ $sectionKey }}')" class="rounded-md bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">
                                    Add item
                                </button>
                            </div>
                        @elseif ($this->isAssocSection($sectionValue))
                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach ($sectionValue as $field => $value)
                                    <div class="{{ $this->isImageField($field, $value) || (is_string($value) && strlen((string) $value) > 80) ? 'sm:col-span-2' : '' }}" wire:key="field-{{ $sectionKey }}-{{ $field }}">
                                        @if ($this->isImageField($field, $value))
                                            <x-cms.image-field
                                                :label="str($field)->replace('_', ' ')->title()"
                                                :current="is_string(data_get($content, $sectionKey.'.'.$field)) ? data_get($content, $sectionKey.'.'.$field) : null"
                                                :model="'uploads.'.$sectionKey.'.'.$field"
                                                hint="Choose a new image to replace the current one when you save."
                                            />
                                            <x-text-input wire:model="content.{{ $sectionKey }}.{{ $field }}" class="mt-2 block w-full" placeholder="Or paste an image path" />
                                        @else
                                            <x-input-label :for="'content-'.$sectionKey.'-'.$field" :value="str($field)->replace('_', ' ')->title()" />
                                            @if (is_string($value) && strlen($value) > 80)
                                                <textarea id="content-{{ $sectionKey }}-{{ $field }}" wire:model="content.{{ $sectionKey }}.{{ $field }}" rows="4" class="mt-1 block w-full rounded-md border-gray-300"></textarea>
                                            @else
                                                <x-text-input id="content-{{ $sectionKey }}-{{ $field }}" wire:model="content.{{ $sectionKey }}.{{ $field }}" class="mt-1 block w-full" />
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @if ($this->isImageField($sectionKey, $sectionValue))
                                <x-cms.image-field
                                    :label="str($sectionKey)->replace('_', ' ')->title()"
                                    :current="is_string($sectionValue) ? $sectionValue : null"
                                    :model="'uploads.'.$sectionKey"
                                    hint="Upload replaces this image on save."
                                />
                                <x-text-input wire:model="content.{{ $sectionKey }}" class="mt-2 block w-full" />
                            @elseif (is_string($sectionValue) && strlen($sectionValue) > 80)
                                <textarea wire:model="content.{{ $sectionKey }}" rows="4" class="mt-1 block w-full rounded-md border-gray-300"></textarea>
                            @else
                                <x-text-input wire:model="content.{{ $sectionKey }}" class="mt-1 block w-full" />
                            @endif
                        @endif
                    </div>
                @endforeach
            </fieldset>
        @endif

        <fieldset class="grid gap-4 border-t pt-5 sm:grid-cols-2">
            <legend class="mb-3 font-semibold">SEO</legend>
            @foreach (['seo_title' => 'SEO title', 'seo_description' => 'SEO description', 'seo_keywords' => 'SEO keywords', 'og_title' => 'OG title', 'og_description' => 'OG description'] as $field => $label)
                <div class="{{ in_array($field, ['seo_description', 'og_description'], true) ? 'sm:col-span-2' : '' }}">
                    <x-input-label :for="$field" :value="$label" />
                    <x-text-input :id="$field" wire:model="{{ $field }}" class="mt-1 block w-full" />
                </div>
            @endforeach
            <div class="sm:col-span-2">
                <x-cms.image-field
                    label="OG image"
                    :current="$og_image"
                    model="ogImageUpload"
                    hint="Optional social share image."
                />
            </div>
        </fieldset>

        <label class="flex items-center gap-2">
            <input type="checkbox" wire:model="is_published" class="rounded border-gray-300 text-indigo-600">
            <span class="text-sm">Published</span>
        </label>

        <x-primary-button wire:loading.attr="disabled">Save page</x-primary-button>
    </form>
</div>
