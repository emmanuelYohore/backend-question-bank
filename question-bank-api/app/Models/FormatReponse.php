<?php

namespace App\Models;

use App\Enums\FormatReponseType;
use Illuminate\Database\Eloquent\Model;

class FormatReponse extends Model
{
    protected $table = "format_reponses";
    protected $fillable = ["type"] ;

    protected $casts = [
    'type' => FormatReponseType::class,
    ];


    public function items()
    {
        return $this->hasMany(Item::class, 'format_reponse_id');
    }

    public function modaliteReponses()
    {
        return $this->hasMany(ModaliteReponse::class);
    }

}
