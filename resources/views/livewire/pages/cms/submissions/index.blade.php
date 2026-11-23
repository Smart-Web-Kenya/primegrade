<?php

use App\Models\Cms\FormSubmission;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    #[Url]
    public string $type = 'all';

    #[Url]
    public string $status = 'all';

    public string $search = '';

    public ?int $deleting = null;

    public function delete(): void
    {
        FormSubmission::query()->findOrFail($this->deleting)->delete();
        $this->deleting = null;
        $this->dispatch('close-modal', 'delete-submission');
    }

    public function with(): array
    {
        $query = FormSubmission::query()
            ->when($this->type !== 'all', fn ($q) => $q->where('type', $this->type))
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($this->search, function ($q) {
                $term = '%'.$this->search.'%';
                $q->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('subject', 'like', $term)
                        ->orWhere('student_name', 'like', $term)
                        ->orWhere('grade_applying', 'like', $term);
                });
            })
            ->latest();

        return [
            'submissions' => $query->get(),
            'counts' => [
                'all' => FormSubmission::query()->count(),
                'contact' => FormSubmission::query()->ofType(FormSubmission::TYPE_CONTACT)->count(),
                'admission' => FormSubmission::query()->ofType(FormSubmission::TYPE_ADMISSION)->count(),
                'new' => FormSubmission::query()->unread()->count(),
            ],
        ];
    }
}; ?>

<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Form submissions</h1>
        <p class="text-sm text-gray-500">Contact messages and admission requests from the website.</p>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach (['all' => 'All ('.$counts['all'].')', 'contact' => 'Contact ('.$counts['contact'].')', 'admission' => 'Admissions ('.$counts['admission'].')'] as $value => $label)
            <button
                type="button"
                wire:click="$set('type', '{{ $value }}')"
                class="rounded-md px-3 py-1.5 text-sm {{ $type === $value ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 shadow' }}"
            >{{ $label }}</button>
        @endforeach
        <button
            type="button"
            wire:click="$set('status', '{{ $status === 'new' ? 'all' : 'new' }}')"
            class="rounded-md px-3 py-1.5 text-sm {{ $status === 'new' ? 'bg-amber-500 text-white' : 'bg-white text-gray-700 shadow' }}"
        >Unread ({{ $counts['new'] }})</button>
    </div>

    <x-text-input wire:model.live.debounce.300ms="search" placeholder="Search by name, email, phone…" class="w-full max-w-md" />

    <div class="overflow-hidden rounded-lg bg-white shadow">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs uppercase text-gray-500">From</th>
                    <th class="px-5 py-3 text-left text-xs uppercase text-gray-500">Type</th>
                    <th class="px-5 py-3 text-left text-xs uppercase text-gray-500">Details</th>
                    <th class="px-5 py-3 text-left text-xs uppercase text-gray-500">Received</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($submissions as $item)
                    <tr class="{{ $item->status === 'new' ? 'bg-indigo-50/40' : '' }}">
                        <td class="px-5 py-4">
                            <p class="font-medium text-gray-900">
                                {{ $item->name }}
                                @if ($item->status === 'new')
                                    <span class="ms-1 rounded-full bg-indigo-100 px-2 py-0.5 text-xs text-indigo-700">New</span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">{{ $item->email }}</p>
                            @if ($item->phone)<p class="text-xs text-gray-400">{{ $item->phone }}</p>@endif
                        </td>
                        <td class="px-5 py-4 text-sm">{{ $item->typeLabel() }}</td>
                        <td class="px-5 py-4 text-sm text-gray-600">
                            @if ($item->isAdmission())
                                {{ $item->student_name }} · {{ $item->grade_applying }}
                            @else
                                {{ $item->subject ?: '—' }}
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-500">{{ $item->created_at->diffForHumans() }}</td>
                        <td class="space-x-3 px-5 py-4 text-right">
                            <a href="{{ route('cms.submissions.show', $item) }}" wire:navigate class="text-sm text-indigo-600">View</a>
                            <button
                                type="button"
                                wire:click="$set('deleting', {{ $item->id }})"
                                x-on:click="$dispatch('open-modal', 'delete-submission')"
                                class="text-sm text-red-600"
                            >Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-500">No submissions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-modal name="delete-submission" focusable>
        <div class="p-6">
            <h2 class="text-lg font-semibold">Delete submission?</h2>
            <p class="mt-2 text-sm text-gray-600">This permanently removes the message from the inbox.</p>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close-modal', 'delete-submission')">Cancel</x-secondary-button>
                <x-danger-button wire:click="delete">Delete</x-danger-button>
            </div>
        </div>
    </x-modal>
</div>
