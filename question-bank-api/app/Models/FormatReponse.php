<?php

namespace App\Models;

use App\Enums\FormatReponseType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormatReponse extends Model
{
    use HasFactory;
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'AQUALI_format_reponses';

    protected $fillable = ['type'];

    protected $casts = [
        'type' => FormatReponseType::class,
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'format_reponse_id', 'id');
    }

    public function modaliteReponses()
    {
        return $this->hasMany(ModaliteReponse::class, 'format_reponse_id', 'id');
    }
}