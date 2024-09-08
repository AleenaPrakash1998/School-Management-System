<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidStudent implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the user exists and has the 'student' role
        $user = User::find($value);

        if (!$user || !$user->hasRole('student')) {
            $fail('The selected user is not a valid student.');
        }
    }
}
