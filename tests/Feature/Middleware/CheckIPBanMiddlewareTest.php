<?php

namespace Tests\Feature\Middleware;

use Tests\TestCase;
use App\Models\IPBan;
use Carbon\Carbon;

class CheckIPBanMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        IPBan::query()->delete();
    }

    public function test_unbanned_ip_can_access_login_page()
    {
        $response = $this->get(route('login'));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_banned_ip_is_redirected_from_login()
    {
        $ip = '192.168.1.1';
        IPBan::create([
            'ip_address' => $ip,
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->addHours(24)
        ]);

        $response = $this->post(route('login'), [], [
            'REMOTE_ADDR' => $ip
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    public function test_expired_ban_allows_access()
    {
        $ip = '192.168.3.3';
        IPBan::create([
            'ip_address' => $ip,
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->subHours(1)
        ]);

        $response = $this->get(route('login'), [
            'REMOTE_ADDR' => $ip
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_error_message_shown_for_banned_ip()
    {
        $ip = '192.168.4.4';
        IPBan::create([
            'ip_address' => $ip,
            'failed_attempts' => 3,
            'banned_until' => Carbon::now()->addHours(8)
        ]);

        $response = $this->get(route('login'), [
            'REMOTE_ADDR' => $ip
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_approved_ip_can_submit_login_form()
    {
        // Test that an IP without a ban or with expired ban can POST to login
        $response = $this->post(route('login'), [
            'email' => 'nonexistent@test.com',
            'password' => 'password'
        ]);

        // Should get validation error, not ban error
        $response->assertSessionHasErrors();
    }
}
