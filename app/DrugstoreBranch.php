<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DrugstoreBranch extends Model
{
    protected $fillable = [
		'branch', 'drugstore_id'
	];

	public function drugstore()
	{
		return $this->belongsTo('App\Drugstore', 'drugstore_id');
	}
}
