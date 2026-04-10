<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquete extends Model
{
    use HasFactory;

    protected $table = 'enquetes';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_message',
        'end_message',
        'archived',
        'url_enquete'
    ];

    protected $casts = [
        'archived' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bankItems()
    {
        return $this->belongsToMany(BankItem::class, 'enquete_banks');
    }

    public function enqueteBanks()
    {
        return $this->hasMany(EnqueteBank::class);
    }

    public function repondants()
    {
        return $this->belongsToMany(Repondant::class, 'enquete_repondants');
    }
}

