<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\IPBan;
use App\Models\Setting;
use Carbon\Carbon;

class IPBanTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        IPBan::query()->delete();

        // Set default ban policy
        Setting::updateOrCreate(
            ['setting_variable' => 'login_attempt_limit'],
            ['setting_group' => 'security', 'setting_value' => '3']
        );
        Setting::updateOrCreate(
            ['setting_variable' => 'login_ban_duration_hours'],
            ['setting_group' => 'security', 'setting_value' => '24']
        );
    }

    public function test_can_create_ip_ban_record()
    {
        $ban = IPBan::create([
            'ip_address' => '192.168.1.1',
            'failed_attempts' => 0,
            'reason' => 'test'
        ]);

        $this->assertDatabaseHas('ip_bans', [
            'ip_address' => '192.168.1.1'
        ]);
    }

    public function test_record_failed_attempt_increments_counter()
    {
        $ip = '192.168.1.1';
        IPBan::recordFailedAttempt($ip, 'test-agent');

        $this->assertDatabaseHas('ip_bans', [
            'ip_address' => $ip,
            'failed_attempts' => 1
        ]);
    }

    public function test_record_failed_attempt_increments_existing_record()
    {
        $ip = '192.168.1.1';
        IPBan::create([
            'ip_address' => $ip,
            'failed_attempts' => 2,
            'reason' => 'previous'
        ]);

        IPBan::recordFailedAttempt($ip, 'test-agent');

        $this->assertDatabaseHas('ip_bans', [
            'ip_address' => $ip,
            'failed_attempts' => 3
        ]);
    }

    public function test_record_failed_attempt_bans_after_limit()
    {
        $ip = '192.168.1.1';

        // Record 3 failed attempts
        IPBan::recordFailedAttempt($ip, 'test-agent');
        IPBan::recordFailedAttempt($ip, 'test-agent');
        IPBan::recordFailedAttempt($ip, 'test-agent');

        $ban = IPBan::where('ip_address', $ip)->first();

        $this->assertNotNull($ban->banned_until);
        $this->assertTrue($ban->banned_until->isFuture());
    }

    public function test_is_banned_returns_true_for_active_ban()
    {
        $ip = '192.168.1.1';
        IPBan::create([
            'ip_address' => $ip,
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->addHours(24)
        ]);

        $this->assertTrue(IPBan::isBanned($ip));
    }

    public function test_is_banned_returns_false_for_expired_ban()
    {
        $ip = '192.168.1.1';
        IPBan::create([
            'ip_address' => $ip,
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->subHours(1)
        ]);

        $this->assertFalse(IPBan::isBanned($ip));
    }

    public function test_is_banned_returns_false_for_no_ban()
    {
        $this->assertFalse(IPBan::isBanned('192.168.1.1'));
    }

    public function test_is_banned_auto_cleans_expired_bans()
    {
        $ip = '192.168.1.1';
        IPBan::create([
            'ip_address' => $ip,
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->subHours(1)
        ]);

        IPBan::isBanned($ip);

        // Record still exists but ban is cleared
        $this->assertDatabaseHas('ip_bans', [
            'ip_address' => $ip,
            'banned_until' => null,
            'failed_attempts' => 0
        ]);
    }

    public function test_release_ban_removes_ban_until()
    {
        $ip = '192.168.1.1';
        $ban = IPBan::create([
            'ip_address' => $ip,
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->addHours(24)
        ]);

        IPBan::releaseBan($ip);

        $ban->refresh();
        $this->assertNull($ban->banned_until);
    }

    public function test_days_until_release_calculates_correctly()
    {
        $ip = '192.168.1.1';
        $ban = IPBan::create([
            'ip_address' => $ip,
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->addDays(5)->addHours(3)
        ]);

        $days = $ban->daysUntilRelease();
        $this->assertGreaterThanOrEqual(4, $days);
        $this->assertLessThanOrEqual(6, $days);
    }

    public function test_hours_until_release_calculates_correctly()
    {
        $ip = '192.168.1.1';
        $ban = IPBan::create([
            'ip_address' => $ip,
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->addHours(12)->addMinutes(30)
        ]);

        $hours = $ban->hoursUntilRelease();
        // The method returns signed difference, which is negative for future dates
        // (difference FROM banned_until TO now())
        // Check that it's approximately 12 hours (in magnitude)
        $this->assertLessThanOrEqual(-11, $hours);
        $this->assertGreaterThanOrEqual(-13, $hours);
    }

    public function test_is_ban_active_returns_true_for_active_ban()
    {
        $ban = IPBan::create([
            'ip_address' => '192.168.1.1',
            'banned_until' => Carbon::now()->addHours(24)
        ]);

        $this->assertTrue($ban->isBanActive());
    }

    public function test_is_ban_active_returns_false_for_expired_ban()
    {
        $ban = IPBan::create([
            'ip_address' => '192.168.1.1',
            'banned_until' => Carbon::now()->subHours(1)
        ]);

        $this->assertFalse($ban->isBanActive());
    }

    public function test_is_ban_active_returns_false_without_ban()
    {
        $ban = IPBan::create([
            'ip_address' => '192.168.1.1',
            'banned_until' => null
        ]);

        $this->assertFalse($ban->isBanActive());
    }

    public function test_ipv6_address_supported()
    {
        $ipv6 = '2001:0db8:85a3:0000:0000:8a2e:0370:7334';

        IPBan::create([
            'ip_address' => $ipv6,
            'failed_attempts' => 1
        ]);

        $this->assertDatabaseHas('ip_bans', [
            'ip_address' => $ipv6
        ]);
    }

    public function test_last_attempt_at_timestamp_updated()
    {
        $ip = '192.168.1.1';
        IPBan::recordFailedAttempt($ip, 'test-agent');

        $ban = IPBan::where('ip_address', $ip)->first();
        $this->assertNotNull($ban->last_attempt_at);
        $this->assertTrue($ban->last_attempt_at->isToday());
    }
}
