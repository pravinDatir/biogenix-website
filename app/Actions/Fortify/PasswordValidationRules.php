<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
use Throwable;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        try {
            Log::info('PasswordValidationRules.passwordRules  Generating password validation rules');
            return ['required', 'string', Password::default(), 'confirmed'];
        } catch (Throwable $exception) {
            Log::error('Failed to build password validation rules.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}
