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
        'archiver',
        'url_enquete'
    ];

    protected $casts = [
        'archiver' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

