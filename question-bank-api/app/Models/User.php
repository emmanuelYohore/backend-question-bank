<?php

namespace App\Models;

use App\Enums\RoleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'users';

    protected $fillable = ['name', 'surname', 'email', 'password', 'role'];

    protected $hidden = ['password'];

    protected $casts = [
    'role' => RoleType::class,
    ];


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
        return $this->hasMany(BankItem::class);
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
