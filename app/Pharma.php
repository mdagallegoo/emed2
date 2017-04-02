<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pharma extends Model
{
      protected $fillable = [
		'drugstore','drugstore_branch','license', 'user_id'
	];

	public function userInfo()
	{
		return $this->belongsTo('App\User', 'user_id');
	}

	public function manager()
	{
		return $this->belongsTo('App\PharmacyManager', 'manager_id');
	}
}

