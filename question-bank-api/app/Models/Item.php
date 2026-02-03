<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = ['format_reponse_id','question','obligatoire'];

    public function bankItems()
    {
        return $this->belongsToMany(BankItem::class, 'bank_item_items');
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
