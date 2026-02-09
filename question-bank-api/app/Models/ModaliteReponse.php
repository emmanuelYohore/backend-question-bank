<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModaliteReponse extends Model
{
    protected $table = 'modalite_reponses';
    protected $fillable = ['format_reponse_id', 'item_id', 'intitule', 'v1', 'v2'];

    public function formatReponse()
    {
        return $this->belongsTo(FormatReponse::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }
}