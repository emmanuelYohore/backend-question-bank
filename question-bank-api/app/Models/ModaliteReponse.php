<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModaliteReponse extends Model
{
    protected $table = 'modalite_reponses';
    protected $fillable = ['format_reponse_id','intitule','valeur','ordre'] ;

    public function formatReponse()
    {
        return $this->belongsTo(FormatReponse::class);
    }

    public function responses()
    {
        return $this->hasMany(Reponse::class);
    }
}
