<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class PatientActionController extends Controller
{
    public function detachPatient($patientId)
    {
        Auth::user()->doctor->patients()->detach($patientId);
        return redirect()->back();
    }

    public function attachPatient($patientId)
    {
        Auth::user()->doctor->patients()->attach($patientId);
        return redirect()->back();
    }
}
