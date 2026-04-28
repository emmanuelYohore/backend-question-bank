<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BankItemItem extends Model
{
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

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

