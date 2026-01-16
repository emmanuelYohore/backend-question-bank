<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repondant extends Model
{
    protected $table = 'repondants';
    protected $fillable = ['session_id'] ;

    public function reponseEnquetes()
    {
        return $this->hasMany(ReponseEnquete::class);
    }
}
