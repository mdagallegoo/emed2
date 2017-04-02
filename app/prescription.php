<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Prescription extends Model
{
    protected $fillable = [
		'brand',
		'genericname',
		'quantity',
		'dosage',
		'frequency',
		'duration',
		'notes',
		'start',
		'end',
		'patient_id',
		'doctor_id',
		'consultation_id'
	];


	public function lacking()
	{
		return $this->quantity - ($this->total_served ?: 0);
	}

	public function patient()
	{
		return $this->belongsTo('App\Patient', 'patient_id');
	}

	public function doctor()
	{
		return $this->belongsTo('App\Doctor', 'doctor_id');
	}

	public function consultation()
	{
		return $this->belongsTo('App\MedicalHistory', 'consultation_id');
	}

	public function transactions()
	{
		return $this->hasMany('App\TransactionLine', 'prescription_id');
	}

	public function scopeLacking($query){
		return $query->select('prescriptions.*', DB::raw('SUM(IFNULL(transaction_lines.quantity, 0)) AS total_served'))
			->leftJoin('transaction_lines', 'transaction_lines.prescription_id', '=', 'prescriptions.id')
			->groupBy('prescriptions.id')
			->havingRaw('quantity > total_served');
	}


}
