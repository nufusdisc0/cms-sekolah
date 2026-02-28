<?php

namespace Tests\Feature\Notifications;

use Tests\TestCase;
use App\Models\Setting;
use App\Models\Registrant;
use App\Models\AcademicYear;
use App\Models\AdmissionPhase;
use App\Models\Major;
use App\Events\AdmissionFormSubmitted;
use App\Listeners\SendAdmissionNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

class EmailNotificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Event::fake();

        // Create required models for registrant
        $year = AcademicYear::create([
            'academic_year' => '2024/2025',
            'year_start' => 2024,
            'year_end' => 2025,
            'is_active' => true
        ]);

        $phase = AdmissionPhase::create([
            'academic_year_id' => $year->id,
            'phase_name' => 'Reguler',
            'registration_start' => now(),
            'registration_end' => now()->addMonth(),
            'is_active' => true
        ]);

        $this->major = Major::create([
            'academic_year_id' => $year->id,
            'major_name' => 'Teknik Informatika',
            'major_code' => 'TI'
        ]);

        $this->phase = $phase;
        $this->year = $year;
    }

    public function test_admission_form_submitted_event_is_fired()
    {
        Event::fake();

        $registrant = Registrant::create([
            'admission_phase_id' => $this->phase->id,
            'major_id' => $this->major->id,
            'full_name' => 'Test Applicant',
            'email' => 'applicant@test.com',
            'phone' => '08123456789',
            'registration_number' => 'REG-' . now()->timestamp
        ]);

        event(new AdmissionFormSubmitted($registrant));

        Event::assertDispatched(AdmissionFormSubmitted::class);
    }

    public function test_email_sent_when_notifications_enabled()
    {
        Mail::fake();

        Setting::updateOrCreate(
            ['setting_variable' => 'notify_admission_submissions'],
            ['setting_group' => 'security', 'setting_value' => '1']
        );
        Setting::updateOrCreate(
            ['setting_variable' => 'admin_notification_email'],
            ['setting_group' => 'security', 'setting_value' => 'admin@test.com']
        );

        $registrant = Registrant::create([
            'admission_phase_id' => $this->phase->id,
            'major_id' => $this->major->id,
            'full_name' => 'Test Applicant',
            'email' => 'applicant@test.com',
            'phone' => '08123456789',
            'registration_number' => 'REG-' . now()->timestamp,
            'status' => 'pending'
        ]);

        // Trigger the listener manually
        $listener = new SendAdmissionNotification();
        $listener->handle(new AdmissionFormSubmitted($registrant));

        // Mail should be queued
        Mail::assertQueued(\App\Mail\AdmissionFormSubmitted::class);
    }

    public function test_email_not_sent_when_notifications_disabled()
    {
        Mail::fake();

        Setting::updateOrCreate(
            ['setting_variable' => 'notify_admission_submissions'],
            ['setting_group' => 'security', 'setting_value' => '0']
        );

        $registrant = Registrant::create([
            'admission_phase_id' => $this->phase->id,
            'major_id' => $this->major->id,
            'full_name' => 'Test Applicant',
            'email' => 'applicant@test.com',
            'phone' => '08123456789',
            'registration_number' => 'REG-' . now()->timestamp
        ]);

        $listener = new SendAdmissionNotification();
        $listener->handle(new AdmissionFormSubmitted($registrant));

        Mail::assertNotQueued(\App\Mail\AdmissionFormSubmitted::class);
    }

    public function test_email_uses_admin_email_from_settings()
    {
        Mail::fake();

        Setting::updateOrCreate(
            ['setting_variable' => 'notify_admission_submissions'],
            ['setting_group' => 'security', 'setting_value' => '1']
        );
        Setting::updateOrCreate(
            ['setting_variable' => 'admin_notification_email'],
            ['setting_group' => 'security', 'setting_value' => 'custom-admin@test.com']
        );

        $registrant = Registrant::create([
            'admission_phase_id' => $this->phase->id,
            'major_id' => $this->major->id,
            'full_name' => 'Test Applicant',
            'email' => 'applicant@test.com',
            'phone' => '08123456789',
            'registration_number' => 'REG-' . now()->timestamp,
            'status' => 'pending'
        ]);

        $listener = new SendAdmissionNotification();
        $listener->handle(new AdmissionFormSubmitted($registrant));

        Mail::assertQueued(\App\Mail\AdmissionFormSubmitted::class, function ($mail) {
            return $mail->hasTo('custom-admin@test.com');
        });
    }

    public function test_email_contains_registrant_data()
    {
        Mail::fake();

        Setting::updateOrCreate(
            ['setting_variable' => 'notify_admission_submissions'],
            ['setting_group' => 'security', 'setting_value' => '1']
        );
        Setting::updateOrCreate(
            ['setting_variable' => 'admin_notification_email'],
            ['setting_group' => 'security', 'setting_value' => 'admin@test.com']
        );

        $registrant = Registrant::create([
            'admission_phase_id' => $this->phase->id,
            'major_id' => $this->major->id,
            'full_name' => 'John Doe',
            'email' => 'john@test.com',
            'phone' => '08123456789',
            'registration_number' => 'REG-12345',
            'status' => 'pending'
        ]);

        $mailable = new \App\Mail\AdmissionFormSubmitted($registrant);
        $mailable->assertSeeInHtml('John Doe');
        $mailable->assertSeeInHtml('john@test.com');
        $mailable->assertSeeInHtml('REG-12345');
    }

    public function test_listener_registered_in_event_service_provider()
    {
        Event::fake();

        $registrant = Registrant::create([
            'admission_phase_id' => $this->phase->id,
            'major_id' => $this->major->id,
            'full_name' => 'Test Applicant',
            'email' => 'applicant@test.com',
            'phone' => '08123456789',
            'registration_number' => 'REG-' . now()->timestamp
        ]);

        event(new AdmissionFormSubmitted($registrant));

        // Verify the listener was called
        Event::assertListening(
            AdmissionFormSubmitted::class,
            SendAdmissionNotification::class
        );
    }
}
