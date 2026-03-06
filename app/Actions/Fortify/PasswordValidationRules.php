<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        Log::info('PasswordValidationRules.passwordRules  Generating password validation rules');
        return ['required', 'string', Password::default(), 'confirmed'];
    }
}
