<?php

namespace App\Services;

use App\Models\Registrant;
use Carbon\Carbon;

class RegistrationNumberService
{
    /**
     * Generate a unique registration number
     * Format: YEAR-RUNNING_NUMBER (e.g., 2026-00001)
     *
     * @return string
     */
    public static function generate(): string
    {
        $currentYear = Carbon::now()->year;
        $prefix = (string)$currentYear;

        // Get the highest running number for this year
        $lastRegistrant = Registrant::whereYear('created_at', $currentYear)
            ->where('registration_number', 'like', $prefix . '-%')
            ->latest('registration_number')
            ->first();

        if (!$lastRegistrant || !$lastRegistrant->registration_number) {
            $runningNumber = 1;
        } else {
            // Extract the numeric part after the dash
            $parts = explode('-', $lastRegistrant->registration_number);
            $lastNumber = intval(end($parts));
            $runningNumber = $lastNumber + 1;
        }

        // Format with 5-digit zero padding
        return sprintf('%s-%05d', $prefix, $runningNumber);
    }

    /**
     * Generate registration number for a specific registrant
     * If not already set, generates and saves it
     *
     * @param Registrant $registrant
     * @return string
     */
    public static function generateForRegistrant(Registrant $registrant): string
    {
        if ($registrant->registration_number) {
            return $registrant->registration_number;
        }

        $registrationNumber = self::generate();
        $registrant->update(['registration_number' => $registrationNumber]);

        return $registrationNumber;
    }

    /**
     * Parse a registration number into its components
     *
     * @param string $registrationNumber
     * @return array
     */
    public static function parse(string $registrationNumber): array
    {
        $parts = explode('-', $registrationNumber);

        return [
            'year' => intval($parts[0] ?? 0),
            'running_number' => intval($parts[1] ?? 0),
            'formatted_number' => $registrationNumber,
        ];
    }

    /**
     * Get total registrations for a given year
     *
     * @param int $year
     * @return int
     */
    public static function countForYear(int $year): int
    {
        return Registrant::whereYear('created_at', $year)
            ->whereNotNull('registration_number')
            ->count();
    }

    /**
     * Validate a registration number format
     *
     * @param string $registrationNumber
     * @return bool
     */
    public static function isValid(string $registrationNumber): bool
    {
        $pattern = '/^\d{4}-\d{5}$/'; // e.g., 2026-00001
        return preg_match($pattern, $registrationNumber) === 1;
    }
}
