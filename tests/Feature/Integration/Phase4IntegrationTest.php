<?php

namespace Tests\Feature\Integration;

use Tests\TestCase;
use App\Models\IPBan;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class Phase4IntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        IPBan::query()->delete();
        Mail::fake();

        // Create a test user for login attempts
        User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('password123')
        ]);

        // Set security settings
        Setting::updateOrCreate(
            ['setting_variable' => 'login_attempt_limit'],
            ['setting_group' => 'security', 'setting_value' => '3']
        );
        Setting::updateOrCreate(
            ['setting_variable' => 'login_ban_duration_hours'],
            ['setting_group' => 'security', 'setting_value' => '24']
        );
        Setting::updateOrCreate(
            ['setting_variable' => 'ip_ban_enabled'],
            ['setting_group' => 'security', 'setting_value' => '1']
        );
    }

    public function test_ip_ban_system_integration()
    {
        $ip = '192.168.100.1';

        // First failed login attempt
        $response = $this->post(route('login'), [
            'email' => 'test@test.com',
            'password' => 'wrong'
        ], [
            'REMOTE_ADDR' => $ip
        ]);

        $this->assertDatabaseHas('ip_bans', [
            'ip_address' => $ip,
            'failed_attempts' => 1
        ]);

        // Second failed login attempt
        $response = $this->post(route('login'), [
            'email' => 'test@test.com',
            'password' => 'wrong'
        ], [
            'REMOTE_ADDR' => $ip
        ]);

        $this->assertDatabaseHas('ip_bans', [
            'ip_address' => $ip,
            'failed_attempts' => 2
        ]);

        // Third failed login attempt - should trigger ban
        $response = $this->post(route('login'), [
            'email' => 'test@test.com',
            'password' => 'wrong'
        ], [
            'REMOTE_ADDR' => $ip
        ]);

        $ban = IPBan::where('ip_address', $ip)->first();
        $this->assertNotNull($ban->banned_until);
        $this->assertTrue($ban->banned_until->isFuture());
    }

    public function test_banned_ip_cannot_login()
    {
        $ip = '192.168.100.2';

        // Create an active ban
        IPBan::create([
            'ip_address' => $ip,
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->addHours(24),
            'reason' => 'Too many failed attempts'
        ]);

        // Try to login
        $response = $this->post(route('login'), [
            'email' => 'test@test.com',
            'password' => 'password123'
        ], [
            'REMOTE_ADDR' => $ip
        ]);

        $response->assertSessionHasErrors();
        $response->assertSessionHas('error');
    }

    public function test_successful_login_resets_failed_attempts()
    {
        $ip = '192.168.100.3';

        // Record 2 failed attempts
        IPBan::recordFailedAttempt($ip, 'test-agent');
        IPBan::recordFailedAttempt($ip, 'test-agent');

        $this->assertDatabaseHas('ip_bans', [
            'ip_address' => $ip,
            'failed_attempts' => 2
        ]);

        // Successful login - should reset counter
        $response = $this->post(route('login'), [
            'email' => 'test@test.com',
            'password' => 'password123'
        ], [
            'REMOTE_ADDR' => $ip
        ]);

        $response->assertRedirect(route('dashboard'));

        $ban = IPBan::where('ip_address', $ip)->first();
        $this->assertEquals(0, $ban->failed_attempts);
    }

    public function test_admin_can_manage_ip_bans()
    {
        $this->actingAs(User::first());

        // Create a ban
        IPBan::create([
            'ip_address' => '192.168.100.4',
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->addHours(24)
        ]);

        // Access security dashboard
        $response = $this->get(route('backend.security.ip-bans'));
        $response->assertStatus(200);

        // Should see the banned IP
        $response->assertSeeText('192.168.100.4');
    }

    public function test_admin_can_release_ban()
    {
        $this->actingAs(User::first());

        $ban = IPBan::create([
            'ip_address' => '192.168.100.5',
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->addHours(24)
        ]);

        // Release ban
        $response = $this->patch(route('backend.security.release-ban', $ban->id), []);

        $ban->refresh();
        $this->assertNull($ban->banned_until);
    }

    public function test_security_settings_configurable()
    {
        $this->actingAs(User::first());

        // Access security settings
        $response = $this->get(route('settings.index', 'security'));
        $response->assertStatus(200);

        // Update settings
        $response = $this->post(route('settings.update', 'security'), [
            'login_attempt_limit' => '5',
            'login_ban_duration_hours' => '48',
            'recaptcha_enabled' => '1',
            'recaptcha_score_threshold' => '0.6',
            'notify_admission_submissions' => '1'
        ]);

        $this->assertDatabaseHas('settings', [
            'setting_variable' => 'login_attempt_limit',
            'setting_value' => '5'
        ]);

        $this->assertDatabaseHas('settings', [
            'setting_variable' => 'login_ban_duration_hours',
            'setting_value' => '48'
        ]);
    }

    public function test_expired_ban_auto_cleanup()
    {
        $ip = '192.168.100.6';

        // Create expired ban
        IPBan::create([
            'ip_address' => $ip,
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->subHours(1)
        ]);

        // Check is_banned should clean up expired record
        $isBanned = IPBan::isBanned($ip);
        $this->assertFalse($isBanned);

        $this->assertDatabaseMissing('ip_bans', [
            'ip_address' => $ip
        ]);
    }

    public function test_ipv6_supported_in_ban_system()
    {
        $ipv6 = '2001:0db8:85a3:0000:0000:8a2e:0370:7334';

        // Record failed attempts with IPv6
        IPBan::recordFailedAttempt($ipv6, 'test-agent');

        $this->assertDatabaseHas('ip_bans', [
            'ip_address' => $ipv6,
            'failed_attempts' => 1
        ]);
    }

    public function test_ban_statistics_displayed()
    {
        $this->actingAs(User::first());

        // Create multiple bans with different statuses
        IPBan::create([
            'ip_address' => '192.168.200.1',
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->addHours(12)
        ]);

        IPBan::create([
            'ip_address' => '192.168.200.2',
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->subHours(12)
        ]);

        $response = $this->get(route('backend.security.ip-bans'));
        $response->assertStatus(200);

        // Should have tabs for different statuses
        $response->assertSeeText('Active');
        $response->assertSeeText('Expired');
    }

    public function test_different_ips_have_separate_ban_counters()
    {
        $ip1 = '192.168.150.1';
        $ip2 = '192.168.150.2';

        // Record attempts for IP1
        IPBan::recordFailedAttempt($ip1, 'test-agent');
        IPBan::recordFailedAttempt($ip1, 'test-agent');

        // Record attempts for IP2
        IPBan::recordFailedAttempt($ip2, 'test-agent');

        $this->assertDatabaseHas('ip_bans', [
            'ip_address' => $ip1,
            'failed_attempts' => 2
        ]);

        $this->assertDatabaseHas('ip_bans', [
            'ip_address' => $ip2,
            'failed_attempts' => 1
        ]);
    }
}
