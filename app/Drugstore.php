<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Drugstore extends Model
{
     protected $fillable = [
		'drugstore'
	];

	public function branches()
	{
		return $this->hasMany('App\DrugstoreBranch', 'drugstore_id');
	}
}
