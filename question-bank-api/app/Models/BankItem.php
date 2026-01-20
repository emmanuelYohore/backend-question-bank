<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankItem extends Model
{
    use HasFactory;

    protected $table = 'bank_items';
    protected $fillable = ['user_id','name','mode','archiver'];

    protected $casts = [
        'archiver' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enquetes()
    {
        return $this->belongsToMany(Enquete::class, 'enquete_banks');

    }

    public function enqueteBank()
    {
        return $this->hasMany(EnqueteBank::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}


