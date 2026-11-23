<?php
use App\Models\Cms\Principle;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
new #[Layout('layouts.app')] class extends Component {
    public function delete(int $id): void {Principle::findOrFail($id)->delete();}
    public function with(): array{return ['principles'=>Principle::orderBy('sort_order')->get()];}
}; ?>
<div class="space-y-6"><div class="flex justify-between"><h1 class="text-2xl font-semibold">Principles</h1><a href="{{ route('cms.principles.create') }}" wire:navigate class="rounded bg-indigo-600 px-4 py-2 text-sm text-white">New principle</a></div><div class="overflow-hidden rounded-lg bg-white shadow"><table class="min-w-full divide-y"><thead class="bg-gray-50"><tr><th class="px-5 py-3 text-left">Title</th><th>Order</th><th>Status</th><th></th></tr></thead><tbody class="divide-y">@forelse($principles as $item)<tr><td class="px-5 py-4"><p class="font-medium">{{ $item->title }}</p><p class="max-w-2xl truncate text-sm text-gray-500">{{ $item->body }}</p></td><td class="text-center">{{ $item->sort_order }}</td><td class="text-center">{{ $item->is_published?'Published':'Draft' }}</td><td class="px-5 text-right"><a href="{{ route('cms.principles.edit',$item) }}" wire:navigate class="mr-3 text-sm text-indigo-600">Edit</a><button wire:click="delete({{ $item->id }})" wire:confirm="Delete this principle?" class="text-sm text-red-600">Delete</button></td></tr>@empty<tr><td colspan="4" class="p-8 text-center text-gray-500">No principles.</td></tr>@endforelse</tbody></table></div></div>
