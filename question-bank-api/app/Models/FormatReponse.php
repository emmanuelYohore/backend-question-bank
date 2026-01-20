<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormatReponse extends Model
{
    protected $table = "format_reponses";
    protected $fillable = ["name", "nb_min_select", "nb_max_select"] ;

    public function items()
    {
        return $this->hasMany(Item::class, 'format_reponse_id');
    }

    public function modaliteReponses()
    {
        return $this->hasMany(ModaliteReponse::class);
    }

}
