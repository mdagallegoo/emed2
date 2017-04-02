<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $fillable = [
		'name',
		'subs'
	];

	protected $casts = [
		'subs' => 'array'
	];

	public function subspecializations()
    {
        return $this->hasMany('App\Subspecialization');
    }


	public function saveSubspecializations(\App\Subspecialization $existing, array $new)
	{
		
	}
}
