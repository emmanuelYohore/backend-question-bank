<?php

namespace App\Models;

use App\Enums\RoleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = ['name', 'surname', 'email', 'password'];

    protected $hidden = ['password'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users')->withTimestamps();
    }

    // public function hasRole(RoleType|string $role): bool
    // {
    //     $roleValue = $role instanceof RoleType ? $role->value : $role;

    //     return $this->roles()->where('name', $roleValue)->exists();
    // }

    public function hasAnyRole(array $roles): bool
    {
        $roleValues = array_map(
            fn (RoleType|string $role) => $role instanceof RoleType ? $role->value : $role,
            $roles
        );

        return $this->roles()->whereIn('name', $roleValues)->exists();
    }

    public function enquetes()
    {
        return $this->hasMany(Enquete::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function bankItems()
    {
        return $this->hasMany(BanKItem::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'user_id' => $this->id,
        ];
    }
}
