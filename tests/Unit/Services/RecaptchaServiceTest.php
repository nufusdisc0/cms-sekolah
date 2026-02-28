<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\RecaptchaService;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RecaptchaServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_recaptcha_service_can_be_instantiated()
    {
        $service = new RecaptchaService();
        $this->assertInstanceOf(RecaptchaService::class, $service);
    }

    public function test_is_enabled_returns_false_when_not_configured()
    {
        Setting::query()->where('setting_variable', 'recaptcha_enabled')->delete();

        $this->assertFalse(RecaptchaService::isEnabled());
    }

    public function test_is_enabled_returns_true_when_configured()
    {
        Setting::updateOrCreate(
            ['setting_variable' => 'recaptcha_enabled'],
            ['setting_group' => 'security', 'setting_value' => '1']
        );

        $this->assertTrue(RecaptchaService::isEnabled());
    }

    public function test_is_configured_requires_both_keys()
    {
        Setting::updateOrCreate(
            ['setting_variable' => 'recaptcha_site_key'],
            ['setting_group' => 'security', 'setting_value' => 'test-site-key']
        );
        Setting::updateOrCreate(
            ['setting_variable' => 'recaptcha_secret_key'],
            ['setting_group' => 'security', 'setting_value' => '']
        );

        $this->assertFalse(RecaptchaService::isConfigured());
    }

    public function test_is_configured_returns_true_with_both_keys()
    {
        Setting::updateOrCreate(
            ['setting_variable' => 'recaptcha_site_key'],
            ['setting_group' => 'security', 'setting_value' => 'test-site-key']
        );
        Setting::updateOrCreate(
            ['setting_variable' => 'recaptcha_secret_key'],
            ['setting_group' => 'security', 'setting_value' => 'test-secret-key']
        );

        $this->assertTrue(RecaptchaService::isConfigured());
    }

    public function test_verify_returns_true_when_disabled()
    {
        Setting::updateOrCreate(
            ['setting_variable' => 'recaptcha_enabled'],
            ['setting_group' => 'security', 'setting_value' => '0']
        );

        $result = RecaptchaService::verify('any-token');
        $this->assertTrue($result);
    }

    public function test_verify_returns_false_for_invalid_token()
    {
        Setting::updateOrCreate(
            ['setting_variable' => 'recaptcha_enabled'],
            ['setting_group' => 'security', 'setting_value' => '1']
        );
        Setting::updateOrCreate(
            ['setting_variable' => 'recaptcha_secret_key'],
            ['setting_group' => 'security', 'setting_value' => 'test-key']
        );

        Http::fake(['*' => Http::response(['success' => false, 'score' => 0.1])]);

        $result = RecaptchaService::verify('invalid-token');
        $this->assertFalse($result);
    }

    public function test_get_score_calls_google_api()
    {
        Setting::updateOrCreate(
            ['setting_variable' => 'recaptcha_secret_key'],
            ['setting_group' => 'security', 'setting_value' => 'test-secret-key']
        );

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.8,
                'action' => 'submit'
            ])
        ]);

        $score = RecaptchaService::getScore('test-token');

        $this->assertEquals(0.8, $score);
    }

    public function test_get_score_is_cached()
    {
        Setting::updateOrCreate(
            ['setting_variable' => 'recaptcha_secret_key'],
            ['setting_group' => 'security', 'setting_value' => 'test-secret-key'
        ]);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.85
            ])
        ]);

        $score1 = RecaptchaService::getScore('test-token');
        $score2 = RecaptchaService::getScore('test-token');

        // If cached, should only make 1 API call
        Http::assertSentCount(1);
        $this->assertEquals($score1, $score2);
    }

    public function test_get_admin_key_returns_from_settings()
    {
        Setting::updateOrCreate(
            ['setting_variable' => 'recaptcha_site_key'],
            ['setting_group' => 'security', 'setting_value' => 'my-admin-key']
        );

        $key = RecaptchaService::getAdminKey();
        $this->assertEquals('my-admin-key', $key);
    }

    public function test_get_secret_key_returns_from_settings()
    {
        Setting::updateOrCreate(
            ['setting_variable' => 'recaptcha_secret_key'],
            ['setting_group' => 'security', 'setting_value' => 'my-secret-key']
        );

        $key = RecaptchaService::getSecretKey();
        $this->assertEquals('my-secret-key', $key);
    }
}
