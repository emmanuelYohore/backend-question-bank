<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Item extends Model
{
    use HasFactory;
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'items';

    protected $fillable = ['user_id', 'format_reponse_id','question','obligatoire', 'archived'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public function modaliteReponses()
    {
        return $this->hasMany(ModaliteReponse::class);
    }
}
