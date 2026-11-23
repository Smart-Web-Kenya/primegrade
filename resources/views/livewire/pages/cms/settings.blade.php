<?php

use App\Models\Cms\{Media, Setting};
use App\Support\CmsMediaUploader;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    use WithFileUploads;

    public array $settings = [];
    public $logo;
    public ?string $currentLogo = null;

    public array $keys = [
        'site_name', 'tagline', 'phone', 'phone_tel', 'email', 'website', 'po_box',
        'location', 'location_detail', 'copyright', 'powered_by_text', 'powered_by_url',
        'motto', 'vision', 'mission', 'alert_text', 'alert_link_text', 'alert_link_url',
    ];

    public function mount(): void
    {
        foreach ($this->keys as $key) {
            $this->settings[$key] = Setting::get($key, '');
        }

        $this->currentLogo = Setting::get('logo');
    }

    public function save(CmsMediaUploader $uploader): void
    {
        $this->validate([
            'settings.*' => ['nullable', 'string', 'max:5000'],
            'logo' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
        ]);

        foreach ($this->settings as $key => $value) {
            if (in_array($key, $this->keys, true)) {
                Setting::set($key, $value);
            }
        }

        if ($this->logo) {
            $path = $uploader->upload($this->logo, 'logos');
            Media::create(['name' => $this->logo->getClientOriginalName(), 'path' => $path, 'mime_type' => $this->logo->getMimeType(), 'size' => $this->logo->getSize(), 'folder' => 'logos']);
            Setting::set('logo', $path);
            $this->currentLogo = $path;
            $this->logo = null;
        }

        session()->flash('success', 'Settings saved.');
    }
}; ?>

<div class="mx-auto max-w-4xl space-y-6">
    <div><h1 class="text-2xl font-semibold">Site Settings</h1><p class="text-sm text-gray-500">Contact details, branding, and homepage messaging.</p></div>
    @if (session('success'))<div class="rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>@endif
    <form wire:submit="save" class="space-y-6 rounded-lg bg-white p-6 shadow">
        <div class="grid gap-5 sm:grid-cols-2">
            @foreach ($keys as $key)
                <div class="{{ in_array($key, ['motto','vision','mission','location_detail','copyright'], true) ? 'sm:col-span-2' : '' }}">
                    <x-input-label :for="$key" :value="str($key)->replace('_', ' ')->title()" />
                    @if (in_array($key, ['motto','vision','mission','location_detail','copyright'], true))
                        <textarea id="{{ $key }}" wire:model="settings.{{ $key }}" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @else
                        <x-text-input id="{{ $key }}" wire:model="settings.{{ $key }}" class="mt-1 block w-full" />
                    @endif
                    <x-input-error :messages="$errors->get('settings.'.$key)" class="mt-2" />
                </div>
            @endforeach
            <div class="sm:col-span-2">
                <x-cms.image-field
                    label="Logo"
                    :current="$currentLogo"
                    model="logo"
                    hint="PNG, JPG, WEBP or SVG. Replaces the current logo on save."
                />
            </div>
        </div>
        <x-primary-button wire:loading.attr="disabled">Save settings</x-primary-button>
    </form>
</div>
