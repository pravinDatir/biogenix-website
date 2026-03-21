<?php

namespace App\Actions\Fortify;

use App\Models\Authorization\Company;
use App\Models\Authorization\User;
use App\Services\Authorization\RolePermissionService;
use App\Services\Authorization\SignupEmailOtpService;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Throwable;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct(
        protected RolePermissionService $rolePermissionService,
        protected SignupEmailOtpService $signupEmailOtpService,
    ) {
    }

    // This validates registration input and creates a user through the Fortify signup flow.
    public function create(array $input): User
    {
        $input = $this->normalizeInput($input);
        $this->validator($input)->validate();
        $this->ensureB2cSignupEmailIsVerified($input);

        try {
            $user = DB::transaction(fn (): User => $this->createUserRecord($input));

            $this->rolePermissionService->assignDefaultRole($user);
            $this->consumeSignupEmailVerification($input);

            Log::info('New user account created successfully.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'user_type' => $user->user_type,
            ]);

            return $user;
        } catch (Throwable $exception) {
            Log::error('Failed to create new user.', ['email' => $input['email'] ?? null, 'error' => $exception->getMessage()]);
            throw ValidationException::withMessages([
                'email' => 'Unable to create the account right now. Please try again.',
            ]);
        }
    }

    private function normalizeInput(array $input): array
    {
        $input['user_type'] = $input['user_type']
            ?? (($input['accountType'] ?? null) === 'business' ? 'b2b' : 'b2c');
        $input['name'] = $input['name']
            ?? trim(((string) ($input['first_name'] ?? '')).' '.((string) ($input['last_name'] ?? '')));

        return $input;
    }

    private function validator(array $input): ValidatorContract
    {
        $b2bTypeOptions = array_keys(config('common.b2b_designation_options', []));

        return Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'user_type' => ['required', Rule::in(['b2c', 'b2b'])],
            'b2b_type' => ['nullable', 'required_if:user_type,b2b', Rule::in($b2bTypeOptions)],
            'company_name' => ['nullable', 'required_if:user_type,b2b', 'string', 'max:255'],
            'company_type' => ['nullable', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'gst_number' => ['nullable', 'required_if:user_type,b2b', 'string', 'max:20'],
            'pan_number' => ['nullable', 'string', 'max:20'],
            'reg_number' => ['nullable', 'string', 'max:255'],
            'established_year' => ['nullable', 'integer', 'min:1900', 'max:'.date('Y')],
            'website' => ['nullable', 'url', 'max:255'],
            'phone' => ['nullable', 'required_if:user_type,b2b', 'string', 'max:20'],
            'alt_phone' => ['nullable', 'string', 'max:20'],
            'address_1' => ['nullable', 'string', 'max:255'],
            'address_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:128'],
            'state' => ['nullable', 'string', 'max:128'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:128'],
            'password' => $this->passwordRules(),
        ]);
    }

    private function createUserRecord(array $input): User
    {
        // Business rule: B2B signup belongs to a company and must wait for approval.
        $company = $this->isB2bSignup($input) ? $this->upsertCompany($input) : null;
        [$status, $approvedAt] = $this->resolveApprovalState($input);

        $user = User::query()->create($this->buildUserAttributes($input, $company, $status, $approvedAt));

        // Business rule: when address is available, save it as the user's default shipping and billing address.
        $this->storeDefaultAddress($user, $input);

        return $user;
    }

    private function isB2bSignup(array $input): bool
    {
        return ($input['user_type'] ?? null) === 'b2b';
    }

    private function isB2cSignup(array $input): bool
    {
        return ($input['user_type'] ?? null) === 'b2c';
    }

    private function ensureB2cSignupEmailIsVerified(array $input): void
    {
        // Business rule: retail signup must verify email before the account can be created.
        if (! $this->isB2cSignup($input)) {
            return;
        }

        $this->signupEmailOtpService->ensureVerifiedEmailOrFail((string) ($input['email'] ?? ''));
    }

    private function consumeSignupEmailVerification(array $input): void
    {
        // Business rule: once signup is completed successfully, the temporary email verification token should not be reused.
        if (! $this->isB2cSignup($input)) {
            return;
        }

        $this->signupEmailOtpService->consumeVerifiedEmail((string) ($input['email'] ?? ''));
    }

    private function upsertCompany(array $input): Company
    {
        return Company::query()->updateOrCreate(
            ['name' => $input['company_name']],
            $this->persistableAttributes('companies', [
                'legal_name' => $input['legal_name'] ?? null,
                'gst_number' => $input['gst_number'] ?? null,
                'pan_number' => $input['pan_number'] ?? null,
                'registration_number' => $input['reg_number'] ?? null,
                'established_year' => isset($input['established_year']) ? (int) $input['established_year'] : null,
                'website' => $input['website'] ?? null,
                'company_type' => $input['company_type'] ?? null,
                'is_active' => true,
            ]),
        );
    }

    private function resolveApprovalState(array $input): array
    {
        return $this->isB2bSignup($input)
            ? ['pending_approval', null]
            : ['active', now()];
    }

    private function buildUserAttributes(array $input, ?Company $company, string $status, $approvedAt): array
    {
        return $this->persistableAttributes('users', [
            'name' => $input['name'],
            'email' => $input['email'],
            'user_type' => $input['user_type'],
            'b2b_type' => $this->isB2bSignup($input) ? ($input['b2b_type'] ?? null) : null,
            'phone' => $input['phone'] ?? null,
            'alt_phone' => $input['alt_phone'] ?? null,
            'company_id' => $company?->id,
            'status' => $status,
            'approved_at' => $approvedAt,
            'approved_by_user_id' => null,
            'created_by_user_id' => null,
            'password' => Hash::make($input['password']),
        ]);
    }

    private function storeDefaultAddress(User $user, array $input): void
    {
        if (! $this->hasAddressPayload($input)) {
            return;
        }

        $addressAttributes = $this->persistableAttributes('user_address', [
            'line1' => $input['address_1'],
            'line2' => $input['address_2'] ?? null,
            'city' => $input['city'],
            'state' => $input['state'],
            'postal_code' => $input['pincode'],
            'country' => $input['country'] ?? 'India',
            'is_default_shipping' => true,
            'is_default_billing' => true,
        ]);

        if ($addressAttributes === []) {
            return;
        }

        $user->addresses()->create($addressAttributes);
    }

    private function hasAddressPayload(array $input): bool
    {
        return Schema::hasTable('user_address')
            && filled($input['address_1'] ?? null)
            && filled($input['city'] ?? null)
            && filled($input['state'] ?? null)
            && filled($input['pincode'] ?? null);
    }

    private function persistableAttributes(string $table, array $attributes): array
    {
        // Business rule: local environments may be on an older schema, so only write columns that exist.
        static $columnCache = [];

        if (! Schema::hasTable($table)) {
            return [];
        }

        if (! isset($columnCache[$table])) {
            $columnCache[$table] = array_flip(Schema::getColumnListing($table));
        }

        return array_filter(
            $attributes,
            fn ($value, $column) => array_key_exists($column, $columnCache[$table]),
            ARRAY_FILTER_USE_BOTH
        );
    }
}
