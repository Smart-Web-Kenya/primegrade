<?php

use App\Models\Cms\FormSubmission;
use App\Services\FormSubmissionService;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:50')]
    public string $phone = '';

    #[Validate('required|string|max:255')]
    public string $subject = '';

    #[Validate('required|string|max:5000')]
    public string $message = '';

    public bool $sent = false;

    public function submit(FormSubmissionService $service): void
    {
        $data = $this->validate();

        $service->store([
            'type' => FormSubmission::TYPE_CONTACT,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?: null,
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);

        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        $this->sent = true;
    }
}; ?>

<div>
    @if ($sent)
        <div class="alert" role="status" style="margin:0 0 1rem;border-radius:.4rem">
            <strong>Thank you.</strong> Your message has been sent. The school office will get back to you soon.
        </div>
    @endif

    <form wire:submit="submit" class="form">
        <div class="row">
            <label>
                Full name
                <input type="text" wire:model="name" autocomplete="name" required>
                @error('name') <span style="color:#9b1c1c;font-size:.85rem;font-weight:500">{{ $message }}</span> @enderror
            </label>
            <label>
                Email
                <input type="email" wire:model="email" autocomplete="email" required>
                @error('email') <span style="color:#9b1c1c;font-size:.85rem;font-weight:500">{{ $message }}</span> @enderror
            </label>
        </div>
        <div class="row">
            <label>
                Phone
                <input type="tel" wire:model="phone" autocomplete="tel">
                @error('phone') <span style="color:#9b1c1c;font-size:.85rem;font-weight:500">{{ $message }}</span> @enderror
            </label>
            <label>
                Subject
                <input type="text" wire:model="subject" required>
                @error('subject') <span style="color:#9b1c1c;font-size:.85rem;font-weight:500">{{ $message }}</span> @enderror
            </label>
        </div>
        <label>
            Message
            <textarea wire:model="message" rows="5" required></textarea>
            @error('message') <span style="color:#9b1c1c;font-size:.85rem;font-weight:500">{{ $message }}</span> @enderror
        </label>
        <button class="btn" type="submit" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="submit">Send message</span>
            <span wire:loading wire:target="submit">Sending…</span>
        </button>
    </form>
</div>
