<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnqueteBank extends Model
{
    protected $table = 'enquete_banks';
    protected $fillable = ['enquete_id','bank_item_id','mode','ordre'];

    public function enquete()
    {
        return $this->belongsTo(Enquete::class);
    }

    public function bankItem()
    {
        return $this->belongsTo(BankItem::class);
    }
}

