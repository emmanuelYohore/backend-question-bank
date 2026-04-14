<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankItemItem extends Model
{
    protected $table = 'bank_item_items';
    protected $fillable = ['bank_item_id','item_id', 'ordre'];

    public function bankItem()
    {
        return $this->belongsTo(BankItem::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

