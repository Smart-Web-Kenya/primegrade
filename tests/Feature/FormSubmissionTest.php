<?php

namespace Tests\Feature;

use App\Mail\FormSubmissionReceived;
use App\Models\Cms\FormSubmission;
use App\Models\Cms\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Volt\Volt;
use Tests\TestCase;

class FormSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::set('email', 'admin@primegradeschool.org');
    }

    public function test_contact_form_saves_submission_and_emails_admin(): void
    {
        Mail::fake();

        Volt::test('forms.contact-form')
            ->set('name', 'Jane Parent')
            ->set('email', 'jane@example.com')
            ->set('phone', '0700 000 000')
            ->set('subject', 'School tour')
            ->set('message', 'We would like to visit next week.')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('sent', true);

        $this->assertDatabaseHas('cms_form_submissions', [
            'type' => FormSubmission::TYPE_CONTACT,
            'email' => 'jane@example.com',
            'subject' => 'School tour',
            'status' => FormSubmission::STATUS_NEW,
        ]);

        Mail::assertSent(FormSubmissionReceived::class, function (FormSubmissionReceived $mail) {
            return $mail->hasTo('admin@primegradeschool.org')
                && $mail->submission->email === 'jane@example.com';
        });
    }

    public function test_admission_form_saves_submission_and_emails_admin(): void
    {
        Mail::fake();

        Volt::test('forms.admission-form')
            ->set('parent_name', 'John Parent')
            ->set('student_name', 'Alex Parent')
            ->set('email', 'john@example.com')
            ->set('phone', '0711 111 111')
            ->set('grade_applying', 'Grade 4')
            ->set('message', 'Interested in boarding.')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('sent', true);

        $this->assertDatabaseHas('cms_form_submissions', [
            'type' => FormSubmission::TYPE_ADMISSION,
            'student_name' => 'Alex Parent',
            'grade_applying' => 'Grade 4',
            'status' => FormSubmission::STATUS_NEW,
        ]);

        Mail::assertSent(FormSubmissionReceived::class);
    }

    public function test_admin_can_view_submissions_in_cms(): void
    {
        $user = User::factory()->create();
        $submission = FormSubmission::query()->create([
            'type' => FormSubmission::TYPE_CONTACT,
            'name' => 'Jane Parent',
            'email' => 'jane@example.com',
            'subject' => 'Hello',
            'message' => 'Testing inbox',
            'status' => FormSubmission::STATUS_NEW,
        ]);

        $this->actingAs($user);

        $this->get(route('cms.submissions.index'))
            ->assertOk()
            ->assertSee('Jane Parent')
            ->assertSee('Contact message');

        $this->get(route('cms.submissions.show', $submission))
            ->assertOk()
            ->assertSee('Testing inbox');

        $this->assertSame(FormSubmission::STATUS_READ, $submission->fresh()->status);
    }

    public function test_contact_page_includes_both_forms(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Contact form')
            ->assertSee('Request for admission')
            ->assertSeeVolt('forms.contact-form')
            ->assertSeeVolt('forms.admission-form');
    }
}
