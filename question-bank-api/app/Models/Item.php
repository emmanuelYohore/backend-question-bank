<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = ['bank_item_id','format_reponse_id','question','ordre','obligatoire'];

    public function bankItem()
    {
        return $this->belongsTo(BankItem::class);
    }

    public function formatReponse()
    {
        return $this->belongsTo(FormatReponse::class);
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }
}
