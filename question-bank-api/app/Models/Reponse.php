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

    protected $table = 'AQUALI_reponses'; 

    protected $fillable = [
        'repondant_id',
        'enquete_id',
        'item_id',
        'modalite_reponse_id',
        'valeur_texte',
        'valeur_evn'
    ];

    protected $casts = [];

    public function repondant()
    {
        return $this->belongsTo(Repondant::class, 'repondant_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function enquete()
    {
        return $this->belongsTo(Enquete::class, 'enquete_id', 'id');
    }

    public function modaliteReponse()
    {
        return $this->belongsTo(ModaliteReponse::class, 'modalite_reponse_id', 'id');
    }
}