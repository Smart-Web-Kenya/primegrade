<?php
use App\Models\Cms\Principle;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
new #[Layout('layouts.app')] class extends Component {
    public ?Principle $principle=null;public string $title='',$body='';public int $sort_order=0;public bool $is_published=true;
    public function mount(?Principle $principle=null): void {$this->principle=$principle;if($principle?->exists)$this->fill($principle->only(['title','body','sort_order','is_published']));}
    public function save(): void {$data=$this->validate(['title'=>['required','string','max:255'],'body'=>['required','string'],'sort_order'=>['integer'],'is_published'=>['boolean']]);$item=$this->principle?->exists?tap($this->principle)->update($data):Principle::create($data);session()->flash('success','Principle saved.');$this->redirect(route('cms.principles.edit',$item),navigate:true);}
}; ?>
<div class="mx-auto max-w-3xl space-y-6"><div class="flex justify-between"><h1 class="text-2xl font-semibold">{{ $principle?->exists?'Edit':'New' }} Principle</h1><a href="{{ route('cms.principles.index') }}" wire:navigate class="text-sm text-indigo-600">Back</a></div>@if(session('success'))<div class="rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>@endif
<form wire:submit="save" class="space-y-5 rounded-lg bg-white p-6 shadow"><div><x-input-label for="title" value="Title"/><x-text-input id="title" wire:model="title" class="mt-1 block w-full"/><x-input-error :messages="$errors->get('title')" class="mt-2"/></div><div><x-input-label for="body" value="Body"/><textarea id="body" wire:model="body" rows="8" class="mt-1 block w-full rounded-md border-gray-300"></textarea><x-input-error :messages="$errors->get('body')" class="mt-2"/></div><div><x-input-label for="sort_order" value="Sort order"/><x-text-input id="sort_order" type="number" wire:model="sort_order" class="mt-1 block w-40"/></div><label class="flex gap-2"><input type="checkbox" wire:model="is_published" class="rounded"> Published</label><x-primary-button>Save principle</x-primary-button></form></div>
