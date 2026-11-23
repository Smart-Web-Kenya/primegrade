<?php

use App\Models\Cms\Program;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public function delete(int $id): void { Program::findOrFail($id)->delete(); }
    public function with(): array { return ['programs' => Program::orderBy('sort_order')->get()]; }
}; ?>

<div class="space-y-6"><div class="flex justify-between"><h1 class="text-2xl font-semibold">Programs</h1><a href="{{ route('cms.programs.create') }}" wire:navigate class="rounded bg-indigo-600 px-4 py-2 text-sm text-white">New program</a></div>
<div class="overflow-hidden rounded-lg bg-white shadow"><table class="min-w-full divide-y"><thead class="bg-gray-50"><tr><th class="px-5 py-3 text-left">Title</th><th>Grades</th><th>Order</th><th>Status</th><th></th></tr></thead><tbody class="divide-y">
@forelse($programs as $item)<tr><td class="px-5 py-4 font-medium">{{ $item->title }} @if($item->is_featured)<span class="text-xs text-amber-600">Featured</span>@endif</td><td class="text-center text-sm">{{ $item->grades_label }}</td><td class="text-center">{{ $item->sort_order }}</td><td class="text-center text-sm">{{ $item->is_published ? 'Published' : 'Draft' }}</td><td class="px-5 text-right"><a href="{{ route('cms.programs.edit',$item) }}" wire:navigate class="mr-3 text-sm text-indigo-600">Edit</a><button wire:click="delete({{ $item->id }})" wire:confirm="Delete this program?" class="text-sm text-red-600">Delete</button></td></tr>@empty<tr><td colspan="5" class="p-8 text-center text-gray-500">No programs.</td></tr>@endforelse
</tbody></table></div></div>
