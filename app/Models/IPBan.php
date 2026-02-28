<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class IPBan extends Model
{
    use HasFactory;

    protected $table = 'ip_bans';
    protected $guarded = [];
    protected $casts = [
        'failed_attempts' => 'integer',
        'last_attempt_at' => 'datetime',
        'banned_until' => 'datetime',
    ];

    /**
     * Record a failed login attempt for an IP
     */
    public static function recordFailedAttempt(string $ip, string $userAgent = ''): void
    {
        // Sanitize IP address
        $ip = filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '127.0.0.1';

        try {
            $ban = self::firstOrCreate(
                ['ip_address' => $ip],
                [
                    'failed_attempts' => 0,
                    'user_agent' => $userAgent,
                ]
            );

            // Increment failed attempts
            $ban->increment('failed_attempts');
            $ban->update([
                'last_attempt_at' => now(),
                'user_agent' => $userAgent,
            ]);

            // Get ban settings
            $attemptLimit = (int) (Setting::where('setting_group', 'security')
                ->where('setting_variable', 'login_attempt_limit')
                ->value('setting_value') ?? 3);

            $banDurationHours = (int) (Setting::where('setting_group', 'security')
                ->where('setting_variable', 'login_ban_duration_hours')
                ->value('setting_value') ?? 24);

            // If failed attempts exceed limit, ban the IP
            if ($ban->failed_attempts >= $attemptLimit) {
                $ban->update([
                    'banned_until' => now()->addHours($banDurationHours),
                    'reason' => "Maximum login attempts ($attemptLimit) exceeded",
                ]);

                Log::warning("IP address banned", [
                    'ip' => $ip,
                    'attempts' => $ban->failed_attempts,
                    'banned_until' => $ban->banned_until,
                ]);

                // Notify admin if configured
                if (Setting::where('setting_group', 'security')
                    ->where('setting_variable', 'email_on_ip_ban')
                    ->value('setting_value') === '1') {
                    // TODO: Send email notification to admin
                }
            } else {
                Log::info("Failed login attempt recorded", [
                    'ip' => $ip,
                    'attempt' => $ban->failed_attempts,
                    'limit' => $attemptLimit,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error recording failed login attempt: ' . $e->getMessage());
        }
    }

    /**
     * Check if an IP address is currently banned
     */
    public static function isBanned(string $ip): bool
    {
        try {
            $ip = filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '127.0.0.1';

            $ban = self::where('ip_address', $ip)
                ->where('banned_until', '>', now())
                ->first();

            if ($ban) {
                Log::warning('Banned IP attempted access', [
                    'ip' => $ip,
                    'banned_until' => $ban->banned_until,
                ]);
                return true;
            }

            // Clean up expired bans
            self::where('banned_until', '<=', now())
                ->update(['banned_until' => null, 'failed_attempts' => 0]);

            return false;
        } catch (\Exception $e) {
            Log::error('Error checking IP ban status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Release a ban for an IP address
     */
    public static function releaseBan(string $ip): bool
    {
        try {
            $ip = filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '127.0.0.1';

            $updated = self::where('ip_address', $ip)->update([
                'banned_until' => null,
                'failed_attempts' => 0,
                'reason' => null,
            ]);

            if ($updated) {
                Log::info('IP ban released', ['ip' => $ip]);
            }

            return $updated > 0;
        } catch (\Exception $e) {
            Log::error('Error releasing IP ban: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get number of days until ban release
     */
    public function daysUntilRelease(): ?int
    {
        if (!$this->banned_until) {
            return null;
        }

        return (int) $this->banned_until->diffInDays(now());
    }

    /**
     * Get hours until ban release
     */
    public function hoursUntilRelease(): ?float
    {
        if (!$this->banned_until) {
            return null;
        }

        return $this->banned_until->diffInHours(now(), false);
    }

    /**
     * Check if this ban record is currently active
     */
    public function isBanActive(): bool
    {
        return $this->banned_until && $this->banned_until > now();
    }

    /**
     * Reset attempt counter
     */
    public function resetAttempts(): void
    {
        $this->update(['failed_attempts' => 0]);
    }
}
