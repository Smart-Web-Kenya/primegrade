<?php

namespace App\Services;

use App\Mail\FormSubmissionReceived;
use App\Models\Cms\FormSubmission;
use App\Models\Cms\Setting;
use Illuminate\Support\Facades\Mail;

class FormSubmissionService
{
    public function store(array $data): FormSubmission
    {
        $submission = FormSubmission::query()->create([
            ...$data,
            'status' => FormSubmission::STATUS_NEW,
            'ip_address' => request()->ip(),
        ]);

        $this->notifyAdmin($submission);

        return $submission;
    }

    public function notifyAdmin(FormSubmission $submission): void
    {
        $adminEmail = Setting::get('email', config('mail.from.address'));

        if (! filled($adminEmail)) {
            return;
        }

        Mail::to($adminEmail)->send(new FormSubmissionReceived($submission));
    }
}
