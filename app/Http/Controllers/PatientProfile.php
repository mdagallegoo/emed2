<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;

class PatientProfile extends Controller
{
    public function showProfile(Patient $patient)
    {
        return view('patient-profile', compact('patient'));
    }

    public function check($uid)
    {
        $patient = Patient::whereUid($uid)->first();
        return response()->json([
            'exists' => !empty($patient),
            'user_id' => !empty($patient) ? $patient->id : null 
        ]);
        
    }

}
