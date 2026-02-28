<?php

namespace App\Listeners;

use App\Events\AdmissionFormSubmitted;
use App\Mail\AdmissionFormSubmitted as AdmissionFormSubmittedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SendAdmissionNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AdmissionFormSubmitted $event): void
    {
        // Check if notifications are enabled
        $notificationEnabled = DB::table('settings')
            ->where('group', 'security')
            ->where('key', 'notify_admission_submissions')
            ->value('value');

        if (!$notificationEnabled || $notificationEnabled !== '1') {
            return;
        }

        // Get admin email from settings
        $adminEmail = DB::table('settings')
            ->where('group', 'security')
            ->where('key', 'admin_notification_email')
            ->value('value');

        if (!$adminEmail) {
            return;
        }

        // Send email notification
        Mail::to($adminEmail)->send(new AdmissionFormSubmittedMail($event->registrant));
    }
}
