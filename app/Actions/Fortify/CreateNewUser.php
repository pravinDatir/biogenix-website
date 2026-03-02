<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\RolePermissionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct(
        protected RolePermissionService $rolePermissionService,
    ) {
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, mixed>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'user_type' => ['required', Rule::in(['b2c', 'b2b'])],
            'b2b_type' => [
                'nullable',
                'required_if:user_type,b2b',
                Rule::in(['dealer', 'distributor', 'lab', 'hospital']),
            ],
            'company_name' => ['nullable', 'required_if:user_type,b2b', 'string', 'max:255'],
            'password' => $this->passwordRules(),
        ])->validate();

        $userId = DB::transaction(function () use ($input): int {
            $companyId = null;
            $status = 'active';
            $approvedAt = now();

            if ($input['user_type'] === 'b2b') {
                $company = DB::table('companies')
                    ->where('name', $input['company_name'])
                    ->first();

                $companyId = $company?->id
                    ?? DB::table('companies')->insertGetId([
                        'name' => $input['company_name'],
                        'company_type' => $input['b2b_type'],
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                $status = 'pending_approval';
                $approvedAt = null;
            }

            return DB::table('users')->insertGetId([
                'name' => $input['name'],
                'email' => $input['email'],
                'user_type' => $input['user_type'],
                'b2b_type' => $input['user_type'] === 'b2b' ? $input['b2b_type'] : null,
                'company_id' => $companyId,
                'status' => $status,
                'approved_at' => $approvedAt,
                'approved_by_user_id' => null,
                'created_by_user_id' => null,
                'password' => Hash::make($input['password']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        $user = User::query()->findOrFail($userId);
        $this->rolePermissionService->assignDefaultRole($user);

        return $user;
    }
}
