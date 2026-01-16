<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnqueteBank extends Model
{
    protected $table = 'enquete_banks';
    protected $fillable = ['enquete_id','bank_items_id','ordre' ];
}
