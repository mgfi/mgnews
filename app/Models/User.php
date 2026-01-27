<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'utype',
        'permissions',
        'created_by',
        'must_change_password',
        'is_active',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'email_verified_at'    => 'datetime',
        'password'             => 'hashed',
        'permissions'          => 'array',
        'must_change_password' => 'boolean',
        'is_active'            => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | User types
    |--------------------------------------------------------------------------
    */
    public const TYPE_ADMIN = 'ADM';
    public const TYPE_USER  = 'USR';

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function isAdmin(): bool
    {
        return $this->utype === self::TYPE_ADMIN;
    }

    public function isOperator(): bool
    {
        return $this->utype === self::TYPE_USER;
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */
    public function hasPermission(string $permission): bool
    {
        // Admin ma wszystko
        if ($this->isAdmin()) {
            return true;
        }

        return in_array($permission, $this->permissions ?? [], true);
    }

    public function givePermissions(array $permissions): void
    {
        $this->permissions = array_values(array_unique($permissions));
        $this->save();
    }
}
