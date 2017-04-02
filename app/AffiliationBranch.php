<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffiliationBranch extends Model
{
    protected $fillable = [
        'name',
        'affiliation_id'
    ];

    public function affiliation()
    {
        return $this->belongsTo('App\Affiliation');
    }
}
