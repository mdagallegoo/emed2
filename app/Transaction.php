<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'patient_id',
        'pharmacist_id'
    ];

    public function lines()
    {
        return $this->hasMany('App\TransactionLine', 'transaction_id');
    }
}
