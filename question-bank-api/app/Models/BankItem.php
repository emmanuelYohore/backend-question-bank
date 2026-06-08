<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BankItem extends Model
{
    use HasFactory;
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'AQUALI_bank_items';

    protected $fillable = ['user_id', 'name', 'archived'];

    protected $casts = [
        'archived' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function enquetes()
    {
        return $this->belongsToMany(Enquete::class, 'AQUALI_enquete_banks'); 
    }

    public function enqueteBank()
    {
        return $this->hasMany(EnqueteBank::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'AQUALI_bank_item_items')
                    ->withPivot('ordre')
                    ->withTimestamps();
    }
}