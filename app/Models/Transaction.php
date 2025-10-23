<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'id',
        'item',
        'amount',
        'fee',
        'buyer_id',
        'seller_id',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array', // ✅ Ini akan auto decode JSON ke array
    ];

    public $incrementing = false; // Sebab guna UUID
    protected $keyType = 'string';
}
