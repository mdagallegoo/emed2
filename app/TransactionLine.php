<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionLine extends Model
{
    protected $fillable = [
        'transaction_id',
        'prescription_id',
        'quantity'
    ];

    protected $casts = [
        'voided' => 'boolean'
    ];
}
