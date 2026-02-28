<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    const GOOGLE_RECAPTCHA_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    const CACHE_PREFIX = 'recaptcha_token_';
    const CACHE_TTL = 60; // 1 minute

    /**
     * Check if reCAPTCHA is enabled
     */
    public static function isEnabled(): bool
    {
        try {
            $enabled = Setting::where('setting_group', 'security')
                ->where('setting_variable', 'recaptcha_enabled')
                ->value('setting_value');

            return $enabled === '1' || $enabled === true;
        } catch (\Exception $e) {
            Log::warning('Error checking reCAPTCHA enabled status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get reCAPTCHA site key
     */
    public static function getAdminKey(): ?string
    {
        try {
            return Setting::where('setting_group', 'security')
                ->where('setting_variable', 'recaptcha_site_key')
                ->value('setting_value');
        } catch (\Exception $e) {
            Log::warning('Error getting reCAPTCHA site key: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get reCAPTCHA secret key
     */
    public static function getSecretKey(): ?string
    {
        try {
            return Setting::where('setting_group', 'security')
                ->where('setting_variable', 'recaptcha_secret_key')
                ->value('setting_value');
        } catch (\Exception $e) {
            Log::warning('Error getting reCAPTCHA secret key: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get reCAPTCHA score threshold
     */
    public static function getScoreThreshold(): float
    {
        try {
            $threshold = Setting::where('setting_group', 'security')
                ->where('setting_variable', 'recaptcha_score_threshold')
                ->value('setting_value');

            return (float) ($threshold ?? 0.5);
        } catch (\Exception $e) {
            Log::warning('Error getting reCAPTCHA score threshold: ' . $e->getMessage());
            return 0.5;
        }
    }

    /**
     * Get reCAPTCHA score for a token
     */
    public static function getScore(string $token, string $action = 'submit'): ?float
    {
        // Check cache first
        $cacheKey = self::CACHE_PREFIX . md5($token);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $secretKey = self::getSecretKey();

        if (empty($secretKey)) {
            Log::warning('reCAPTCHA secret key not configured');
            return null;
        }

        try {
            $response = Http::timeout(5)->post(self::GOOGLE_RECAPTCHA_VERIFY_URL, [
                'secret' => $secretKey,
                'response' => $token,
            ]);

            if ($response->failed()) {
                Log::error('reCAPTCHA API request failed: ' . $response->status());
                return null;
            }

            $data = $response->json();

            if (!isset($data['score']) || !isset($data['success'])) {
                Log::error('Invalid reCAPTCHA response format: ' . json_encode($data));
                return null;
            }

            if (!$data['success']) {
                Log::warning('reCAPTCHA validation failed: ' . json_encode($data));
                return null;
            }

            $score = (float) $data['score'];

            // Cache the score
            Cache::put($cacheKey, $score, self::CACHE_TTL);

            // Log the score for monitoring
            Log::info('reCAPTCHA score received', [
                'score' => $score,
                'action' => $data['action'] ?? 'unknown',
                'challenge_ts' => $data['challenge_ts'] ?? null,
            ]);

            return $score;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA API error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verify reCAPTCHA token
     */
    public static function verify(string $token, string $action = 'submit', ?float $threshold = null): bool
    {
        // If reCAPTCHA is disabled, pass validation
        if (!self::isEnabled()) {
            return true;
        }

        if (empty($token)) {
            Log::warning('reCAPTCHA token is empty');
            return false;
        }

        $score = self::getScore($token, $action);

        if ($score === null) {
            // If we can't get a score, fail the validation
            Log::error('Failed to get reCAPTCHA score');
            return false;
        }

        // Use provided threshold or get from settings
        if ($threshold === null) {
            $threshold = self::getScoreThreshold();
        }

        $passed = $score >= $threshold;

        Log::info('reCAPTCHA verification', [
            'score' => $score,
            'threshold' => $threshold,
            'passed' => $passed,
        ]);

        return $passed;
    }

    /**
     * Check if reCAPTCHA is properly configured
     */
    public static function isConfigured(): bool
    {
        $siteKey = self::getAdminKey();
        $secretKey = self::getSecretKey();

        return !empty($siteKey) && !empty($secretKey);
    }
}
