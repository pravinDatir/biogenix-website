<?php

namespace App\Services\Authorization;

use App\Services\Notification\EmailNotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class SignupEmailOtpService
{
    public function __construct(
        protected EmailNotificationService $emailNotificationService,
    ) {
    }

    // This sends one email OTP for B2C signup after checking resend rules.
    public function sendOtpForB2cSignup(string $email): array
    {
        try {
            // Step 1: normalize the email once so cache keys and verification lookups stay consistent.
            $normalizedEmail = $this->normalizeEmail($email);
            $activeOtpPayload = $this->getActiveOtpPayload($normalizedEmail);

            // Step 2: stop repeated sends during the resend cooldown window.
            $this->ensureOtpCanBeResent($normalizedEmail, $activeOtpPayload);

            // Step 3: reuse one still-active OTP for the same email so the customer is not confused by multiple valid-looking emails.
            $otpCode = $this->resolveOtpCode($activeOtpPayload);
            $expiryMinutes = $this->otpExpiryMinutes();
            $expiresAt = $activeOtpPayload
                ? Carbon::parse($activeOtpPayload['expires_at'])
                : now()->addMinutes($expiryMinutes);

            // Step 4: store the OTP securely in cache so the signup flow stays simple and temporary.
            Cache::put($this->otpCacheKey($normalizedEmail), [
                'otp_code' => $otpCode,
                'otp_hash' => $this->hashOtp($normalizedEmail, $otpCode),
                'sent_at' => now()->toIso8601String(),
                'expires_at' => $expiresAt->toIso8601String(),
                'failed_attempts' => 0,
            ], $expiresAt);

            // Step 5: clear any earlier verified state because a new OTP should create one fresh verification cycle.
            Cache::forget($this->verifiedCacheKey($normalizedEmail));

            // Step 6: send the OTP through the shared email notification flow.
            $this->emailNotificationService->sendSignupEmailOtp($normalizedEmail, $otpCode, $expiryMinutes);

            Log::info('Signup email OTP sent successfully.', [
                'email' => $normalizedEmail,
                'expires_at' => $expiresAt->toDateTimeString(),
            ]);

            // Step 7: return a small response payload that the signup screen can show immediately.
            return [
                'message' => $activeOtpPayload
                    ? 'OTP sent again. Please use the latest email you received.'
                    : 'OTP sent to your email successfully.',
                'expires_in_minutes' => $expiryMinutes,
                'resend_available_in_seconds' => $this->resendCooldownSeconds(),
            ];
        } catch (ValidationException $exception) {
            Log::warning('Signup email OTP send blocked by business validation.', [
                'email' => $email,
                'errors' => $exception->errors(),
            ]);

            throw $exception;
        } catch (Throwable $exception) {
            Log::error('Failed to send signup email OTP.', [
                'email' => $email,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    // This verifies the submitted OTP and marks the email as approved for B2C signup.
    public function verifyOtpForB2cSignup(string $email, string $otp): array
    {
        try {
            // Step 1: normalize the input once so OTP lookup and verified cache use one stable key.
            $normalizedEmail = $this->normalizeEmail($email);
            $normalizedOtp = trim($otp);

            // Step 2: load the current OTP session for this email and stop when no active OTP exists.
            $otpPayload = Cache::get($this->otpCacheKey($normalizedEmail));

            if (! is_array($otpPayload)) {
                throw ValidationException::withMessages([
                    'otp' => 'Please request a new OTP first.',
                ]);
            }

            // Step 3: stop the flow cleanly when the OTP has already expired.
            $expiresAt = Carbon::parse($otpPayload['expires_at'] ?? now()->subSecond());

            if ($expiresAt->isPast()) {
                Cache::forget($this->otpCacheKey($normalizedEmail));

                throw ValidationException::withMessages([
                    'otp' => 'This OTP has expired. Please request a new OTP.',
                ]);
            }

            // Step 4: block further attempts when the customer has already exhausted the allowed invalid tries.
            $failedAttempts = (int) ($otpPayload['failed_attempts'] ?? 0);

            if ($failedAttempts >= $this->maxOtpAttempts()) {
                Cache::forget($this->otpCacheKey($normalizedEmail));

                throw ValidationException::withMessages([
                    'otp' => 'Too many invalid OTP attempts. Please request a new OTP.',
                ]);
            }

            // Step 5: compare the submitted OTP with the stored secure hash.
            if (! hash_equals((string) ($otpPayload['otp_hash'] ?? ''), $this->hashOtp($normalizedEmail, $normalizedOtp))) {
                $failedAttempts++;

                // Step 6: update the failed-attempt count so repeated invalid OTP guesses are limited.
                Cache::put($this->otpCacheKey($normalizedEmail), [
                    'otp_code' => $otpPayload['otp_code'] ?? null,
                    'otp_hash' => $otpPayload['otp_hash'],
                    'sent_at' => $otpPayload['sent_at'] ?? now()->toIso8601String(),
                    'expires_at' => $expiresAt->toIso8601String(),
                    'failed_attempts' => $failedAttempts,
                ], $expiresAt);

                Log::warning('Invalid signup email OTP entered.', [
                    'email' => $normalizedEmail,
                    'failed_attempts' => $failedAttempts,
                ]);

                throw ValidationException::withMessages([
                    'otp' => $failedAttempts >= $this->maxOtpAttempts()
                        ? 'Too many invalid OTP attempts. Please request a new OTP.'
                        : 'The entered OTP is not valid. Please try again.',
                ]);
            }

            // Step 7: store one short-lived verified marker so the final signup submit can trust this email.
            Cache::put($this->verifiedCacheKey($normalizedEmail), [
                'email' => $normalizedEmail,
                'verified_at' => now()->toIso8601String(),
            ], now()->addMinutes($this->verifiedWindowMinutes()));

            // Step 8: remove the used OTP because the email has already been confirmed successfully.
            Cache::forget($this->otpCacheKey($normalizedEmail));

            Log::info('Signup email OTP verified successfully.', [
                'email' => $normalizedEmail,
            ]);

            return [
                'message' => 'Email verified successfully. You can continue with signup now.',
            ];
        } catch (ValidationException $exception) {
            Log::warning('Signup email OTP verification was rejected by business validation.', [
                'email' => $email,
                'errors' => $exception->errors(),
            ]);

            throw $exception;
        } catch (Throwable $exception) {
            Log::error('Failed to verify signup email OTP.', [
                'email' => $email,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    // This protects final account creation so only OTP-verified B2C emails can register.
    public function ensureVerifiedEmailOrFail(string $email): void
    {
        $normalizedEmail = $this->normalizeEmail($email);
        $verifiedPayload = Cache::get($this->verifiedCacheKey($normalizedEmail));

        if (! is_array($verifiedPayload) || ($verifiedPayload['email'] ?? null) !== $normalizedEmail) {
            throw ValidationException::withMessages([
                'email' => 'Please verify your email with OTP before continuing.',
            ]);
        }
    }

    // This helps the signup page restore verified-email UI state after other validation errors return the user back to the form.
    public function isVerifiedForB2cSignup(string $email): bool
    {
        $normalizedEmail = $this->normalizeEmail($email);
        $verifiedPayload = Cache::get($this->verifiedCacheKey($normalizedEmail));

        return is_array($verifiedPayload) && ($verifiedPayload['email'] ?? null) === $normalizedEmail;
    }

    // This clears the temporary verified marker after a successful final signup create.
    public function consumeVerifiedEmail(string $email): void
    {
        Cache::forget($this->verifiedCacheKey($this->normalizeEmail($email)));
    }

    // This ensures resend requests respect the configured cooldown time.
    protected function ensureOtpCanBeResent(string $email, ?array $otpPayload = null): void
    {
        if (! is_array($otpPayload) || empty($otpPayload['sent_at'])) {
            return;
        }

        $nextAllowedAt = Carbon::parse($otpPayload['sent_at'])->addSeconds($this->resendCooldownSeconds());

        if ($nextAllowedAt->isFuture()) {
            throw ValidationException::withMessages([
                'email' => 'Please wait before requesting another OTP.',
            ]);
        }
    }

    // This returns one active OTP payload only when the current OTP is still valid for the email.
    protected function getActiveOtpPayload(string $email): ?array
    {
        $otpPayload = Cache::get($this->otpCacheKey($email));

        if (! is_array($otpPayload)) {
            return null;
        }

        $expiresAt = Carbon::parse($otpPayload['expires_at'] ?? now()->subSecond());

        if ($expiresAt->isPast()) {
            Cache::forget($this->otpCacheKey($email));

            return null;
        }

        return $otpPayload;
    }

    // This keeps one stable OTP code active during the valid window so repeated resend actions do not confuse the customer.
    protected function resolveOtpCode(?array $otpPayload): string
    {
        if (is_array($otpPayload) && filled($otpPayload['otp_code'] ?? null)) {
            return (string) $otpPayload['otp_code'];
        }

        return (string) random_int(100000, 999999);
    }

    // This creates one stable cache key for the active OTP record.
    protected function otpCacheKey(string $email): string
    {
        return 'signup_email_otp:'.sha1($email);
    }

    // This creates one stable cache key for the verified-email marker.
    protected function verifiedCacheKey(string $email): string
    {
        return 'signup_email_verified:'.sha1($email);
    }

    // This keeps email matching consistent across send, verify, and final signup submit.
    protected function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    // This generates a simple secure OTP hash without storing the plain OTP in cache.
    protected function hashOtp(string $email, string $otp): string
    {
        return hash('sha256', $email.'|'.$otp.'|'.config('app.key'));
    }

    // This returns the OTP expiry window from config.
    protected function otpExpiryMinutes(): int
    {
        return (int) config('common.signup_email_otp.expiry_minutes', 10);
    }

    // This returns the resend cooldown time from config.
    protected function resendCooldownSeconds(): int
    {
        return (int) config('common.signup_email_otp.resend_cooldown_seconds', 60);
    }

    // This returns the temporary verified-email lifetime from config.
    protected function verifiedWindowMinutes(): int
    {
        return (int) config('common.signup_email_otp.verified_window_minutes', 30);
    }

    // This returns the maximum number of invalid OTP attempts allowed.
    protected function maxOtpAttempts(): int
    {
        return (int) config('common.signup_email_otp.max_attempts', 5);
    }
}
