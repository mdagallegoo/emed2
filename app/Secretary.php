<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Secretary extends Model
{
 protected $fillable = [
		'attainment', 'user_id'
	];

	public function userInfo()
	{
		return $this->belongsTo('App\User', 'user_id');
	}

	public function doctor()
	{
		 return $this->belongsTo('App\Doctor', 'doctor_id');
	}

}
