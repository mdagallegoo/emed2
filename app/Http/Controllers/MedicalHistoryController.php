<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DoctorRequest;
use Validator;
use App\Doctor;
use App\User;
use App\Patient;
use App\MedicalHistory;
use Auth;

class MedicalHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('consultations.patient-notes');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $patientId = $request->input('patient_id');

        $rules = [
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'bloodpressure' => 'required',
            'temperature' => 'required|numeric',
            'pulserate' => 'present',
            'resprate' => 'present',
            // 'allergyquestion' => 'required|in:Y,N',
            // 'allergyname' => 'present|required_if:allergyquestion,Y',
            // 'pastsakit' => 'present',
            // 'immunization' => 'present',
            // 'surgeryprocedure' => 'present',
            'notes' => 'required',
            'chiefcomplaints' => 'required'
            // ,            
            // 'medications' => 'required'
        ];

        $this->validate($request, $rules);

        $data = $request->only(array_keys($rules));
        $data['patient_id'] = $patientId;

        Auth::user()->doctor->consultations()->create($data); 

        return redirect()
            ->intended(route('patients.show', ['id' => $patientId]))
            ->with('ACTION_RESULT', [
                'type' => 'success', 
                'message' => 'New consultation has been saved successfully!'
            ]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        MedicalHistory::destroy($id);
        return redirect()->back();
    }
}
