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

    protected $table = 'AQUALI_items'; // ✅ Corrigé

    protected $fillable = [
        'user_id',
        'format_reponse_id',
        'question',
        'min_case_to_check',
        'max_case_to_check',
        'obligatoire',
        'nom_court',
        'archived'
    ];

    protected $casts = [
        'obligatoire' => 'boolean',
        'archived' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bankItems()
    {
        return $this->belongsToMany(BankItem::class, 'AQUALI_bank_item_items')
                    ->withPivot('ordre')
                    ->withTimestamps();
    }

    public function formatReponse()
    {
        return $this->belongsTo(FormatReponse::class, 'format_reponse_id', 'id');
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class, 'item_id', 'id');
    }

    public function modaliteReponses()
    {
        return $this->hasMany(ModaliteReponse::class, 'item_id', 'id');
    }
}