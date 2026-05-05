<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reponse extends Model
{
    use HasFactory;
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'reponses';

    protected $fillable = [
        'repondant_id',
        'enquete_id',
        'item_id',
        'modalite_reponse_id',
        'valeur_texte',
        'valeur_evn'
    ];

    protected $casts = [
        'completed'=> 'boolean',
    ];

    public function repondant()
    {
        return $this->belongsTo(Repondant::class);
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
