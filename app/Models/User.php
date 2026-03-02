<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'user_type',
        'b2b_type',
        'company_id',
        'status',
        'approved_at',
        'approved_by_user_id',
        'created_by_user_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'company_id' => 'integer',
            'approved_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isB2c(): bool
    {
        return $this->user_type === 'b2c';
    }

    public function isB2b(): bool
    {
        return $this->user_type === 'b2b';
    }

    public function isAdmin(): bool
    {
        return in_array($this->user_type, ['admin', 'delegated_admin'], true);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPendingApproval(): bool
    {
        return $this->status === 'pending_approval';
    }
}
