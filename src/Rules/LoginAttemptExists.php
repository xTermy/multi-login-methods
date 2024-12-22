<?php

namespace StormCode\MultiLoginMethods\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use StormCode\MultiLoginMethods\Models\LoginAttempt;

class LoginAttemptExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $decryptedToken = decrypt($value);
        } catch (\Exception $e) {
            $fail('Provided value is not a valid token.');
            return;
        }

        if (!is_array($decryptedToken) || !isset($decryptedToken['user_id']) || !isset($decryptedToken['attempt_id']) || !isset($decryptedToken['method'])) {
            $fail('Provided value is not a valid token.');
            return;
        }

        if (LoginAttempt::where('user_id', $decryptedToken['user_id'])->where('id', $decryptedToken['attempt_id'])->where('succeed', false)->count() === 0) {
            $fail('Login attempt does not exist.');
        }
    }
}
