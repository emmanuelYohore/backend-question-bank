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

    protected $table = 'modalite_reponses';
    protected $fillable = ['format_reponse_id', 'item_id', 'intitule','ordre', 'v1', 'v2'];

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