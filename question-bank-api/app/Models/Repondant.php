<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Repondant extends Model
{
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'repondants';
    protected $fillable = ['session_id','started_at','completed_at'];

    public function enquetes()
    {
        return $this->belongsToMany(Enquete::class, 'enquete_repondants');
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }
}
