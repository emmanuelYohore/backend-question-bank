<?php

namespace App\Models;

use App\Enums\ModeType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EnqueteBank extends Model
{
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'enquete_banks';
    protected $fillable = ['enquete_id','bank_item_id','ordre','mode'];

    //casts
    protected $casts = [
        'mode' => ModeType::class,
    ];

    public function enquete()
    {
        return $this->belongsTo(Enquete::class);
    }

    public function bankItem()
    {
        return $this->belongsTo(BankItem::class);
    }
}

