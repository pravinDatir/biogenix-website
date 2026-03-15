<?php

namespace App\Actions\Fortify;

use App\Models\Authorization\Company;
use App\Models\Authorization\User;
use App\Services\Authorization\RolePermissionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
    ) {
    }

    // This validates registration input and creates a user through the Fortify signup flow.
    public function create(array $input): User
    {
        try {
            $input['user_type'] = $input['user_type']
                ?? (($input['accountType'] ?? null) === 'business' ? 'b2b' : 'b2c');
            $input['name'] = $input['name']
                ?? trim(((string) ($input['first_name'] ?? '')).' '.((string) ($input['last_name'] ?? '')));

            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
                'user_type' => ['required', Rule::in(['b2c', 'b2b'])],
                'b2b_type' => ['nullable', 'required_if:user_type,b2b', Rule::in(['dealer', 'distributor', 'lab', 'hospital'])],
                'company_name' => ['nullable', 'required_if:user_type,b2b', 'string', 'max:255'],
                'legal_name' => ['nullable', 'string', 'max:255'],
                'gst_number' => ['nullable', 'required_if:user_type,b2b', 'string', 'max:20'],
                'pan_number' => ['nullable', 'string', 'max:20'],
                'reg_number' => ['nullable', 'string', 'max:255'],
                'established_year' => ['nullable', 'integer', 'min:1900', 'max:'.date('Y')],
                'website' => ['nullable', 'url', 'max:255'],
                'designation' => ['nullable', 'string', 'max:255'],
                'phone' => ['nullable', 'required_if:user_type,b2b', 'string', 'max:20'],
                'alt_phone' => ['nullable', 'string', 'max:20'],
                'address_1' => ['nullable', 'string', 'max:255'],
                'address_2' => ['nullable', 'string', 'max:255'],
                'city' => ['nullable', 'string', 'max:128'],
                'state' => ['nullable', 'string', 'max:128'],
                'pincode' => ['nullable', 'string', 'max:20'],
                'country' => ['nullable', 'string', 'max:128'],
                'password' => $this->passwordRules(),
            ])->validate();

            $user = DB::transaction(function () use ($input): User {
                $company = null;
                $status = 'active';
                $approvedAt = now();

                if (($input['user_type'] ?? null) === 'b2b') {
                    $company = Company::query()->updateOrCreate(
                        ['name' => $input['company_name']],
                        [
                            'legal_name' => $input['legal_name'] ?? null,
                            'gst_number' => $input['gst_number'] ?? null,
                            'pan_number' => $input['pan_number'] ?? null,
                            'registration_number' => $input['reg_number'] ?? null,
                            'established_year' => isset($input['established_year']) ? (int) $input['established_year'] : null,
                            'website' => $input['website'] ?? null,
                            'company_type' => $input['b2b_type'],
                            'is_active' => true,
                        ],
                    );

                    $status = 'pending_approval';
                    $approvedAt = null;
                }

                $user = User::query()->create([
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'user_type' => $input['user_type'],
                    'b2b_type' => ($input['user_type'] ?? null) === 'b2b' ? $input['b2b_type'] : null,
                    'designation' => $input['designation'] ?? null,
                    'phone' => $input['phone'] ?? null,
                    'alt_phone' => $input['alt_phone'] ?? null,
                    'company_id' => $company?->id,
                    'status' => $status,
                    'approved_at' => $approvedAt,
                    'approved_by_user_id' => null,
                    'created_by_user_id' => null,
                    'password' => Hash::make($input['password']),
                ]);

                if (
                    filled($input['address_1'] ?? null)
                    && filled($input['city'] ?? null)
                    && filled($input['state'] ?? null)
                    && filled($input['pincode'] ?? null)
                ) {
                    $user->addresses()->create([
                        'line1' => $input['address_1'],
                        'line2' => $input['address_2'] ?? null,
                        'city' => $input['city'],
                        'state' => $input['state'],
                        'postal_code' => $input['pincode'],
                        'country' => $input['country'] ?? 'India',
                        'is_default_shipping' => true,
                        'is_default_billing' => true,
                    ]);
                }

                return $user;
            });

            $this->rolePermissionService->assignDefaultRole($user);

            return $user;
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            Log::error('Failed to create new user.', ['email' => $input['email'] ?? null, 'error' => $exception->getMessage()]);
            throw ValidationException::withMessages([
                'email' => 'Unable to create the account right now. Please try again.',
            ]);
        }
    }
}
