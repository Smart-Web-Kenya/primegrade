<?php

use App\Models\Cms\Program;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public ?Program $program = null;
    public string $title = '';
    public string $slug = '';
    public string $subtitle = '';
    public string $grades_label = '';
    public string $summary = '';
    public string $body = '';
    public string $boarding_note = '';
    public array $bullets = [];
    public array $meta_tags = [];
    public int $sort_order = 0;
    public bool $is_published = true;
    public bool $is_featured = false;

    public function mount(?Program $program = null): void
    {
        $this->program = $program;

        if ($program?->exists) {
            $this->title = (string) $program->title;
            $this->slug = (string) $program->slug;
            $this->subtitle = (string) $program->subtitle;
            $this->grades_label = (string) $program->grades_label;
            $this->summary = (string) $program->summary;
            $this->body = (string) ($program->body ?? '');
            $this->boarding_note = (string) ($program->boarding_note ?? '');
            $this->sort_order = (int) $program->sort_order;
            $this->is_published = (bool) $program->is_published;
            $this->is_featured = (bool) $program->is_featured;
            $this->bullets = array_values($program->bullets ?? []);
            $this->meta_tags = array_values($program->meta_tags ?? []);
        }
    }

    public function updatedTitle(): void
    {
        if (! $this->program?->exists || $this->slug === '') {
            $this->slug = Str::slug($this->title);
        }
    }

    public function addBullet(): void
    {
        $this->bullets[] = '';
    }

    public function removeBullet(int $index): void
    {
        unset($this->bullets[$index]);
        $this->bullets = array_values($this->bullets);
    }

    public function addMetaTag(): void
    {
        $this->meta_tags[] = '';
    }

    public function removeMetaTag(int $index): void
    {
        unset($this->meta_tags[$index]);
        $this->meta_tags = array_values($this->meta_tags);
    }

    public function save(): void
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('cms_programs', 'slug')->ignore($this->program?->id)],
            'subtitle' => ['required', 'string', 'max:255'],
            'grades_label' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string'],
            'body' => ['nullable', 'string'],
            'bullets' => ['array'],
            'bullets.*' => ['nullable', 'string', 'max:500'],
            'boarding_note' => ['nullable', 'string', 'max:255'],
            'meta_tags' => ['array'],
            'meta_tags.*' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['integer'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['bullets'] = collect($data['bullets'])->map(fn ($v) => trim((string) $v))->filter()->values()->all();
        $data['meta_tags'] = collect($data['meta_tags'])->map(fn ($v) => trim((string) $v))->filter()->values()->all();

        $item = $this->program?->exists
            ? tap($this->program)->update($data)
            : Program::create($data);

        session()->flash('success', 'Program saved.');
        $this->redirect(route('cms.programs.edit', $item), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-4xl space-y-6">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold">{{ $program?->exists ? 'Edit' : 'New' }} Program</h1>
        <a href="{{ route('cms.programs.index') }}" wire:navigate class="text-sm text-indigo-600">Back</a>
    </div>

    @if (session('success'))
        <div class="rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
    @endif

    <form wire:submit="save" class="grid gap-5 rounded-lg bg-white p-6 shadow sm:grid-cols-2">
        @foreach (['title' => 'Title', 'slug' => 'Slug', 'subtitle' => 'Subtitle', 'grades_label' => 'Grades label', 'boarding_note' => 'Boarding note'] as $field => $label)
            <div class="{{ in_array($field, ['title', 'slug'], true) ? 'sm:col-span-2' : '' }}">
                <x-input-label :for="$field" :value="$label" />
                <x-text-input :id="$field" wire:model.blur="{{ $field }}" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get($field)" class="mt-2" />
            </div>
        @endforeach

        <div class="sm:col-span-2">
            <x-input-label for="summary" value="Summary" />
            <textarea id="summary" wire:model="summary" rows="3" class="mt-1 block w-full rounded-md border-gray-300"></textarea>
            <x-input-error :messages="$errors->get('summary')" class="mt-2" />
        </div>

        <div class="sm:col-span-2">
            <x-input-label for="body" value="Body" />
            <textarea id="body" wire:model="body" rows="7" class="mt-1 block w-full rounded-md border-gray-300"></textarea>
            <x-input-error :messages="$errors->get('body')" class="mt-2" />
        </div>

        <div class="sm:col-span-2">
            <x-cms.string-list
                label="Bullets"
                :items="$bullets"
                add-method="addBullet"
                remove-method="removeBullet"
                model-prefix="bullets"
                placeholder="Benefit or feature"
            />
        </div>

        <div class="sm:col-span-2">
            <x-cms.string-list
                label="Meta tags"
                :items="$meta_tags"
                add-method="addMetaTag"
                remove-method="removeMetaTag"
                model-prefix="meta_tags"
                placeholder="e.g. Grades 1–6"
            />
        </div>

        <div>
            <x-input-label for="sort_order" value="Sort order" />
            <x-text-input id="sort_order" type="number" wire:model="sort_order" class="mt-1 block w-full" />
        </div>

        <div class="flex items-end gap-5 pb-2">
            <label><input type="checkbox" wire:model="is_published" class="rounded"> Published</label>
            <label><input type="checkbox" wire:model="is_featured" class="rounded"> Featured</label>
        </div>

        <div class="sm:col-span-2">
            <x-primary-button>Save program</x-primary-button>
        </div>
    </form>
</div>
