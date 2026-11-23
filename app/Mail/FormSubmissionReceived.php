<?php

namespace App\Mail;

use App\Models\Cms\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FormSubmissionReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public FormSubmission $submission)
    {
    }

    public function envelope(): Envelope
    {
        $subject = $this->submission->isAdmission()
            ? 'New admission request from '.$this->submission->name
            : 'New contact message from '.$this->submission->name;

        return new Envelope(
            subject: $subject,
            replyTo: [
                new Address($this->submission->email, $this->submission->name),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.form-submission-received',
        );
    }
}
