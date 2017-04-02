<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliation extends Model
{
    protected $fillable = [
        'name'
    ];

    public function branches()
    {
        return $this->hasMany('App\AffiliationBranch', 'affiliation_id');
    }
}
