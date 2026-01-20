<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repondant extends Model
{
    protected $table = 'repondants';
    protected $fillable = ['enquete_id', 'session_id'];

    public function enquete()
    {
        return $this->belongsTo(Enquete::class);
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }
}
