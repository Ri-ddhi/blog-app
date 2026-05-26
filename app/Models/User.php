<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'email', 'password', 'role', 'is_active'])] // Added 'role' here just in case
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM PERMISSION METHODS
    |--------------------------------------------------------------------------
    |*/

    /**
     * TASK 36 REQUIREMENT:
     * Checks if any role assigned to the user (or direct user override)
     * has access to a specific registered Laravel route name.
     *
     * @param string $routeName e.g. 'posts.edit'
     * @return bool
     */
    public function hasPermissionToRoute(string $routeName): bool
    {
        // 1. Check if there's a direct personal EXCLUDE override for this route
        $excluded = $this->permissions()
            ->where('route_name', $routeName)
            ->wherePivot('type', 'exclude')
            ->exists();

        if ($excluded) {
            return false; // Personally denied — stop here
        }

        // 2. Check if there's a direct personal INCLUDE override for this route
        $directInclude = $this->permissions()
            ->where('route_name', $routeName)
            ->wherePivot('type', 'include')
            ->exists();

        if ($directInclude) {
            return true; // Personally granted — stop here
        }

        $stringRole = \App\Models\Role::where('name', $this->role)->first();
        if ($stringRole && $stringRole->permissions()->where('route_name', $routeName)->exists()) {
            return true;
        }

        // 3. Fallback: Check if ANY of the user's assigned roles has this route permission
        foreach ($this->roles as $role) {
            if ($role->permissions()->where('route_name', $routeName)->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has a specific permission by its plain name.
     *
     * @param string $permissionName  e.g. 'edit blogs'
     */
    public function hasPermission(string $permissionName): bool
    {
        // Step 1: Check if there's a direct EXCLUDE override for this user
        $excluded = $this->permissions()
            ->where('name', $permissionName)
            ->wherePivot('type', 'exclude')
            ->exists();

        if ($excluded) {
            return false;
        }

        // Step 2: Check if there's a direct INCLUDE override for this user
        $directInclude = $this->permissions()
            ->where('name', $permissionName)
            ->wherePivot('type', 'include')
            ->exists();

        if ($directInclude) {
            return true;
        }

        // Step 3: Check if ANY of the user's roles has this permission
        foreach ($this->roles as $role) {
            if ($role->permissions()->where('name', $permissionName)->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Shortcut: check if this user has a role by name
     * Usage: auth()->user()->hasRole('admin')
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    |*/

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user')
            ->withPivot('type');
    }

    public function changedStatuses(): HasMany
    {
        return $this->hasMany(Status::class, 'changed_by');
    }
}
