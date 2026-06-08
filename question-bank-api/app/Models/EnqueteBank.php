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

    protected $table = 'AQUALI_enquete_banks';

    protected $fillable = ['enquete_id', 'bank_item_id', 'ordre', 'mode', 'nombre_items_aleatoires'];

    protected $casts = [
        'mode' => ModeType::class,
    ];

    public function enquete()
    {
        return $this->belongsTo(Enquete::class, 'enquete_id', 'id');
    }

    public function bankItem()
    {
        return $this->belongsTo(BankItem::class, 'bank_item_id', 'id');
    }
}