<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReponseItem extends Model
{
    protected $table = 'reponse_items';
    protected $fillable = ['reponse_enquete_id','item_id','modalite_reponse_id','valeur_texte'] ;

    public function reponseEnquete()
    {
        return $this->belongsTo(ReponseEnquete::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function modaliteReponse()
    {
        return $this->belongsTo(ModaliteReponse::class);
    }
    

}   
