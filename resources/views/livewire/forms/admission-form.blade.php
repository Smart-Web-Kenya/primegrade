<?php

use App\Models\Cms\FormSubmission;
use App\Services\FormSubmissionService;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    #[Validate('required|string|max:255')]
    public string $parent_name = '';

    #[Validate('required|string|max:255')]
    public string $student_name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:50')]
    public string $phone = '';

    #[Validate('required|string|max:255')]
    public string $grade_applying = '';

    #[Validate('nullable|string|max:5000')]
    public string $message = '';

    public bool $sent = false;

    public function submit(FormSubmissionService $service): void
    {
        $data = $this->validate();

        $service->store([
            'type' => FormSubmission::TYPE_ADMISSION,
            'name' => $data['parent_name'],
            'parent_name' => $data['parent_name'],
            'student_name' => $data['student_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'grade_applying' => $data['grade_applying'],
            'subject' => 'Admission request — '.$data['grade_applying'],
            'message' => $data['message'] ?: null,
        ]);

        $this->reset(['parent_name', 'student_name', 'email', 'phone', 'grade_applying', 'message']);
        $this->sent = true;
    }
}; ?>

<div>
    @if ($sent)
        <div class="alert" role="status" style="margin:0 0 1rem;border-radius:.4rem">
            <strong>Request received.</strong> Thank you. The admissions office will contact you about the next steps.
        </div>
    @endif

    <form wire:submit="submit" class="form">
        <div class="row">
            <label>
                Parent / guardian name
                <input type="text" wire:model="parent_name" autocomplete="name" required>
                @error('parent_name') <span style="color:#9b1c1c;font-size:.85rem;font-weight:500">{{ $message }}</span> @enderror
            </label>
            <label>
                Student name
                <input type="text" wire:model="student_name" required>
                @error('student_name') <span style="color:#9b1c1c;font-size:.85rem;font-weight:500">{{ $message }}</span> @enderror
            </label>
        </div>
        <div class="row">
            <label>
                Email
                <input type="email" wire:model="email" autocomplete="email" required>
                @error('email') <span style="color:#9b1c1c;font-size:.85rem;font-weight:500">{{ $message }}</span> @enderror
            </label>
            <label>
                Phone
                <input type="tel" wire:model="phone" autocomplete="tel" required>
                @error('phone') <span style="color:#9b1c1c;font-size:.85rem;font-weight:500">{{ $message }}</span> @enderror
            </label>
        </div>
        <label>
            Grade applying for
            <select wire:model="grade_applying" required>
                <option value="">Select a grade</option>
                <option value="Playgroup">Playgroup</option>
                <option value="PP1">PP1</option>
                <option value="PP2">PP2</option>
                <option value="Grade 1">Grade 1</option>
                <option value="Grade 2">Grade 2</option>
                <option value="Grade 3">Grade 3</option>
                <option value="Grade 4">Grade 4</option>
                <option value="Grade 5">Grade 5</option>
                <option value="Grade 6">Grade 6</option>
                <option value="Grade 7">Grade 7</option>
                <option value="Grade 8">Grade 8</option>
                <option value="Grade 9">Grade 9</option>
            </select>
            @error('grade_applying') <span style="color:#9b1c1c;font-size:.85rem;font-weight:500">{{ $message }}</span> @enderror
        </label>
        <label>
            Additional notes <span style="font-weight:500;color:var(--muted,#667)">(optional)</span>
            <textarea wire:model="message" rows="4" placeholder="Any questions or details that would help the school prepare for your enquiry"></textarea>
            @error('message') <span style="color:#9b1c1c;font-size:.85rem;font-weight:500">{{ $message }}</span> @enderror
        </label>
        <button class="btn" type="submit" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="submit">Submit admission request</span>
            <span wire:loading wire:target="submit">Sending…</span>
        </button>
    </form>
</div>
