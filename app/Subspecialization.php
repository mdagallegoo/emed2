<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subspecialization extends Model
{
    protected $fillable = [
        'name',
        'specialization_id'
    ];

    public function specialization()
    {
        return $this->belongsTo('App\Specialization');
    }
}
