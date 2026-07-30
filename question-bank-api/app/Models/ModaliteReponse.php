<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModaliteReponse extends Model
{
    use HasFactory;
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'AQUALI_modalite_reponses';

    protected $fillable = ['format_reponse_id', 'item_id', 'intitule', 'ordre', 'min_value', 'max_value'];

    public function formatReponse()
    {
        return $this->belongsTo(FormatReponse::class, 'format_reponse_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class, 'modalite_reponse_id', 'id');
    }
}