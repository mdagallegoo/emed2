<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    protected $fillable = [
		'chiefcomplaints','weight','height','bloodpressure','temperature','pulserate','resprate','notes','patient_id','doctor_id'
	];

	public function patient()
	{
		return $this->belongsTo('App\Patient', 'patient_id');
	}

	public function doctor()
	{
		return $this->belongsTo('App\Doctor', 'doctor_id');
	}

	public function prescriptions()
	{
		return $this->hasMany('App\Prescription', 'consultation_id');
	}
}