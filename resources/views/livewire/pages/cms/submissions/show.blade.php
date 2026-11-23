<?php

use App\Models\Cms\FormSubmission;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public FormSubmission $submission;

    public function mount(FormSubmission $submission): void
    {
        $this->submission = $submission;
        $this->submission->markRead();
        $this->submission->refresh();
    }

    public function archive(): void
    {
        $this->submission->update(['status' => FormSubmission::STATUS_ARCHIVED]);
        $this->submission->refresh();
        session()->flash('success', 'Submission archived.');
    }

    public function markUnread(): void
    {
        $this->submission->update([
            'status' => FormSubmission::STATUS_NEW,
            'read_at' => null,
        ]);
        $this->submission->refresh();
        session()->flash('success', 'Marked as unread.');
    }

    public function delete(): void
    {
        $this->submission->delete();
        $this->redirect(route('cms.submissions.index'), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">
                <a href="{{ route('cms.submissions.index') }}" wire:navigate class="text-indigo-600">Form submissions</a>
                / {{ $submission->typeLabel() }}
            </p>
            <h1 class="mt-1 text-2xl font-semibold text-gray-900">{{ $submission->name }}</h1>
            <p class="text-sm text-gray-500">Received {{ $submission->created_at->format('d M Y, H:i') }}</p>
        </div>
        <span class="rounded-full px-3 py-1 text-xs font-medium {{ $submission->status === 'new' ? 'bg-indigo-100 text-indigo-700' : ($submission->status === 'archived' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-700') }}">
            {{ str($submission->status)->title() }}
        </span>
    </div>

    @if (session('success'))
        <div class="rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="space-y-4 rounded-lg bg-white p-6 shadow">
        <dl class="grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Email</dt>
                <dd class="mt-1"><a class="text-indigo-600" href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Phone</dt>
                <dd class="mt-1">
                    @if ($submission->phone)
                        <a class="text-indigo-600" href="tel:{{ preg_replace('/\s+/', '', $submission->phone) }}">{{ $submission->phone }}</a>
                    @else
                        —
                    @endif
                </dd>
            </div>

            @if ($submission->isAdmission())
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Parent / guardian</dt>
                    <dd class="mt-1">{{ $submission->parent_name ?: $submission->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Student name</dt>
                    <dd class="mt-1">{{ $submission->student_name ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Grade applying for</dt>
                    <dd class="mt-1">{{ $submission->grade_applying ?: '—' }}</dd>
                </div>
            @endif

            @if ($submission->subject)
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Subject</dt>
                    <dd class="mt-1">{{ $submission->subject }}</dd>
                </div>
            @endif
        </dl>

        @if ($submission->message)
            <div class="border-t pt-4">
                <h2 class="text-sm font-semibold text-gray-700">Message</h2>
                <p class="mt-2 whitespace-pre-wrap text-gray-700">{{ $submission->message }}</p>
            </div>
        @endif
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="mailto:{{ $submission->email }}?subject={{ urlencode('Re: '.($submission->subject ?: $submission->typeLabel())) }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Reply by email</a>
        @if ($submission->status !== 'archived')
            <button type="button" wire:click="archive" class="rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow hover:bg-gray-50">Archive</button>
        @endif
        @if ($submission->status !== 'new')
            <button type="button" wire:click="markUnread" class="rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow hover:bg-gray-50">Mark unread</button>
        @endif
        <button type="button" wire:click="delete" wire:confirm="Delete this submission?" class="rounded-md px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Delete</button>
    </div>
</div>
