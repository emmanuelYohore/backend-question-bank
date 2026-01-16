<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = ['bank_items_id','format_reponse_id','question','ordre','obligatoire'];

    public function bankItems()
    {
        return $this->hasMany(BankItem::class);
    }

    public function formatReponse()
    {
        return $this->belongsTo(FormatReponse::class);
    }

     public function reponseItems()
    {
        return $this->hasMany(ReponseItem::class);
    }
}
