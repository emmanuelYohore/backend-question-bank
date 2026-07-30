<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Enquete extends Model
{
    use HasFactory;
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'AQUALI_enquetes';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_message',
        'end_message',
        'archived',
        'url_enquete'
    ];

    protected $casts = [
        'archived' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bankItems()
    {
        return $this->belongsToMany(BankItem::class, 'AQUALI_enquete_banks')
                    ->withPivot('id', 'mode', 'ordre')
                    ->withTimestamps()
                    ->orderByPivot('ordre');
    }

    public function enqueteBanks()
    {
        return $this->hasMany(EnqueteBank::class, 'enquete_id', 'id');
    }

    public function repondants()
    {
        return $this->belongsToMany(Repondant::class, 'AQUALI_enquete_repondants');
    }
}