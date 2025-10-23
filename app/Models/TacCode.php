<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TacCode extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'transaction_id',
        'contact',
        'code',
        'created_at',
    ];
}
