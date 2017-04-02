<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Http\Requests\PatientRequest;
use App\User;
use App\Doctor;
use App\Secretary;
use Auth;

class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showHomepage()
    {
        $items = Auth::user()->patient;
        return view('patients.patient-home', [
            'items' => $items
        ]);
    
    }
    
    public function index(Request $request)
    {

        $user = Auth::user();
        $search =  $request->input('search');
       

        if($user->user_type === "DOCTOR"){
            $patients = Auth::user()->doctor->patients();

        if(trim($search)){
            $patients->whereHas('userInfo', function($q) USE($search){
                $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE '%{$search}%'");
            });
        }

            return view('patients.list', [
                'patients' => $patients->get()
            ]);
        }

        else if($user->user_type === "SECRETARY"){
            $patients = Auth::user()->secretary->doctor->patients();

        if(trim($search)){
            $patients->whereHas('userInfo', function($q) USE($search){
                $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE '%{$search}%'");
            });
        }

            return view('patients.list', [
                'patients' => $patients->get()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = Auth::user()->where('username', 'mdag')->get();
        return view('patients.patient-form', [
                'item' => $item
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // validate input
        $this->validate($request, [
                'firstname' => 'required',
                'lastname' => 'required',
                'username' => 'required|unique:users',
                'address' => 'required',
                'birthdate' => 'required|date',
                'avatar' => 'required|image|max:2048'
            ], [
                'firstname.required' => 'Please enter your first name.',
                'lastname.required' => 'Please enter your last name.',
                'username.required' => 'Please enter your username.',
                'address.required' => 'Please enter your address.',
                'avatar.required' => 'Please select profile picture to upload.'
           ]);

        // get fields for user table
        $input = $request->only([
            'username', 
            'firstname', 
            'lastname',
            'address',
            'middle_initial',
            'birthdate',
            'gender',
            'contact_number',
            'address',
            'email',
            'sex'
        ]);

        // verify if username exists
        $credentials = $request->only(['username']);

        // assign password: default is firstname+lastname lowercase
        $input['password'] = bcrypt(strtolower($input['firstname']).strtolower($input['lastname']));
        // assign user type
        $input['user_type'] = 'PATIENT';
        //save to DB (users)
        $user = User::create($input);

        // save to DB      
        $patient = $user->patient()->create([
            'bloodtype' => $request->bloodtype,
            'econtact'=> $request ->econtact,
            'erelationship'=> $request->erelationship,
            'civilstatus'=> $request->civilstatus,
            'bloodtype'=> $request->bloodtype,
            'enumber'=> $request->enumber,
            'nationality'=> $request->nationality,
            'occupation'=> $request->occupation,
            'allergyquestion'=> $request ->allergyquestion,
            'allergyname'=> $request->allergyname,
            'civilstatus'=> $request->civilstatus,
            'bloodtype'=> $request->bloodtype,
            'enumber'=> $request->enumber,
            'nationality'=> $request->nationality,
            'occupation'=> $request->occupation
        ]);

        // connect patient to doctor
        if(Auth::user()->user_type === 'DOCTOR')
            $patient->doctors()->attach(Auth::user()->doctor->id);
        else
            $patient->doctors()->attach(Auth::user()->secretary->doctor->id);

        // save patient's profile picture
        $path = $request->file('avatar')->store(
            'avatars/'.$user->id, 'public'
        );
        $user->avatar = $path;
        $user->save();

        if(Auth::user()->user_type === "DOCTOR")
        {
            return redirect()->route('patients.index');
        }

        else if(Auth::user()->user_type === "SECRETARY")
        {
            return redirect()->route('patients.index');
        }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::user()->user_type === 'DOCTOR')
        {
            $patients = Patient::find($id);
            return view('patients.doc-patienthome', [
                'patients' => $patients
            ]);
        }
        else if(Auth::user()->user_type === 'PATIENT' || Auth::user()->user_type === 'SECRETARY')
        {
            $items = Patient::find($id);
            return view('patients.patient-home', [
                'items' => $items
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


       if(Auth::user()->user_type === "DOCTOR")
        {
             return view('patients.edit', [
            'data' => Patient::with('userInfo')->where('id', $id)->first()
        ]);

       } 

        else if(Auth::user()->user_type === "SECRETARY")
        {
              return view('patients.edit', [
            'data' => Patient::with('userInfo')->where('id', $id)->first()
        ]);
        }
        
        else if(Auth::user()->user_type === "PATIENT")
        {
             return view('patients.edit', [
            'data' => Patient::with('userInfo')->where('id', $id)->first()
        ]);


        }




         // $url = URL::route('home') . '#footer';
         // return Redirect::to($url);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PatientRequest $request, $id)
    {
       $patient = Patient::find($id);
        $patient->fill([
            'bloodtype' => $request->bloodtype,
            'nationality' => $request->nationality,
            'civilstatus'=> $request->civilstatus,
            'erelationship' => $request->erelationship,
            'econtact'=> $request->econtact,
            'enumber'=> $request->enumber,
             'allergyname' => $request->allergyname,
            'allergyquestion' => $request->allergyquestion,
            'past_disease'=> $request->past_disease,
            'past_surgery' => $request->past_surgery,
            'immunization'=> $request->immunization,
            'family_history'=> $request->family_history
        ]);
        $patient->save();

        $user = User::find($patient->user_id);
        $user->fill($request->only([
            'username', 
            'firstname', 
            'lastname',
            'middle_initial',
            'contact_number',
            'sex',
            'email',
            'birthdate',
            'address',

        ]));
        $user->save();
        

       if(Auth::user()->user_type === "DOCTOR")
        {
            return redirect()->route('patients.index');
        }

        else if(Auth::user()->user_type === "SECRETARY")
        {
            return redirect()->route('secretary.index');
        }

        else if(Auth::user()->user_type === "PATIENT")
        {
            $items = Patient::find($id);
        return view('patients.patient-home', [
            'items' => $items
        ]);
        }
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
