<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PharmacyBranch extends Model
{
    protected $fillable = [
        'pharmacy_id',
        'name',
        'address'
    ];

    public function pharmacy()
    {
        return $this->belongsTo('App\Pharmacy');
    }
}
