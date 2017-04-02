<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DoctorRequest;
use Validator;
use App\Doctor;
use App\User;
use App\Patient;
use Auth;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('prescription.prescriptions');
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

        $rules = [
            'patient_id' => 'required',
            'consultation_id' => 'required',
            'genericname' => 'required',
            'brand' => 'present',
            'quantity' => 'required',
            'duration' => 'required',
            'dosage' => 'required',
            'frequency' => 'required',
            'start' => 'required|date_format:"Y-m-d"',
            'end' => 'required|date_format:"Y-m-d"',
            'notes' => 'present'
        ];

        $this->validate($request, $rules, [
            'start.date_format' => 'The start date must be of format MM/DD/YYYY',
            'end.date_format' => 'The end date must be of format MM/DD/YYYY'
        ]);

        $data = $request->only(array_keys($rules));

        Auth::user()->doctor->prescriptions()->create($data); 

        return redirect()
            ->intended(route('patients.show', ['id' => $data['patient_id']]))
            ->with('ACTION_RESULT', [
                'type' => 'success', 
                'message' => 'New prescription has been saved successfully!'
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
    }
}
