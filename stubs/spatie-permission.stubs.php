<?php

/**
 * Stubs for Spatie Laravel Permission
 * This file helps Intelephense recognize the classes
 */

namespace Spatie\Permission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 */
class Role extends Model
{
    /**
     * Give permission to a role
     */
    public function givePermissionTo($permission): self
    {
        return $this;
    }

    /**
     * Revoke permission from a role
     */
    public function revokePermissionTo($permission): self
    {
        return $this;
    }

    /**
     * Get all permissions for this role
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }

    /**
     * Get all users with this role
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'model_has_roles');
    }
}

/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 */
class Permission extends Model
{
    /**
     * Give permission to a role
     */
    public function assignRole($role): self
    {
        return $this;
    }

    /**
     * Revoke permission from a role
     */
    public function removeRole($role): self
    {
        return $this;
    }

    /**
     * Get all roles for this permission
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }

    /**
     * Get all users with this permission
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'model_has_permissions');
    }
}

namespace Spatie\Permission;

class PermissionRegistrar
{
    /**
     * Forget cached permissions
     */
    public function forgetCachedPermissions(): void
    {
    }

    /**
     * Clear cached permissions
     */
    public function clearCachedPermissions(): void
    {
    }

    /**
     * Register permissions
     */
    public function registerPermissions(): void
    {
    }
}
