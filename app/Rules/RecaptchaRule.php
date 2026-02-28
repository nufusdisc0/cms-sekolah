<?php

namespace App\Rules;

use App\Services\RecaptchaService;
use Illuminate\Contracts\Validation\Rule;

class RecaptchaRule implements Rule
{
    protected float $threshold;

    /**
     * Create a new rule instance.
     */
    public function __construct(float $threshold = 0.5)
    {
        $this->threshold = $threshold;
    }

    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        // If reCAPTCHA is not enabled, pass validation
        if (!RecaptchaService::isEnabled()) {
            return true;
        }

        // Verify the token
        return RecaptchaService::verify($value, 'submit', $this->threshold);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'reCAPTCHA verification failed. Please try again.';
    }
}
