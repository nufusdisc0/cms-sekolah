<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default security settings
        $settings = [
            ['setting_group' => 'security', 'setting_variable' => 'recaptcha_enabled', 'setting_value' => '0'],
            ['setting_group' => 'security', 'setting_variable' => 'recaptcha_site_key', 'setting_value' => ''],
            ['setting_group' => 'security', 'setting_variable' => 'recaptcha_secret_key', 'setting_value' => ''],
            ['setting_group' => 'security', 'setting_variable' => 'recaptcha_score_threshold', 'setting_value' => '0.5'],
            ['setting_group' => 'security', 'setting_variable' => 'admin_notification_email', 'setting_value' => ''],
            ['setting_group' => 'security', 'setting_variable' => 'notify_admission_submissions', 'setting_value' => '1'],
            ['setting_group' => 'security', 'setting_variable' => 'include_documents_in_email', 'setting_value' => '0'],
            ['setting_group' => 'security', 'setting_variable' => 'login_attempt_limit', 'setting_value' => '3'],
            ['setting_group' => 'security', 'setting_variable' => 'login_ban_duration_hours', 'setting_value' => '24'],
            ['setting_group' => 'security', 'setting_variable' => 'ip_ban_enabled', 'setting_value' => '1'],
            ['setting_group' => 'security', 'setting_variable' => 'email_on_ip_ban', 'setting_value' => '0'],
        ];

        foreach ($settings as $setting) {
            // Only insert if it doesn't exist
            DB::table('settings')->updateOrInsert(
                [
                    'setting_group' => $setting['setting_group'],
                    'setting_variable' => $setting['setting_variable'],
                ],
                [
                    'setting_value' => $setting['setting_value'],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')
            ->where('setting_group', 'security')
            ->delete();
    }
};
