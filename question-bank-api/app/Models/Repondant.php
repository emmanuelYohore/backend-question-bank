<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repondant extends Model
{
    protected $table = 'repondants';
    protected $fillable = ['session_id','ip_address','completed'];

    public function enquetes()
    {
        return $this->belongsToMany(Enquete::class, 'enquete_repondants');
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }
}
