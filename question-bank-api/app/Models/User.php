<?php

namespace App\Models;

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
        return $this->belongsToMany(Role::class, 'role_users');
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
