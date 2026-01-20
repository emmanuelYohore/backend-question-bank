<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormatReponse extends Model
{
    protected $table = "format_reponses";
    protected $fillable = ["name","type", "nb_min_select", "nb_max_select"] ;

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function modaliteReponses()
    {
        return $this->hasMany(ModaliteReponse::class);
    }

}
