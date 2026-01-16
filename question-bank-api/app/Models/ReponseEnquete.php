<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReponseEnquete extends Model
{
    protected $table = 'reponse_enquetes';
    protected $fillable = ['enquete_id','repondant_id','complete'] ;

    protected $casts = [
        'completed'=> 'boolean'
    ];

    public function enquete()
    {
        return $this->belongsTo(Enquete::class);
    }

    public function reponseItems()
    {
        return $this->hasMany(ReponseItem::class);
    }

    public function repondant()
    {
        return $this->belongsTo(Repondant::class);
    }
}
