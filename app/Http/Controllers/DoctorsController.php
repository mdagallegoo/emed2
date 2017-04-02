<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DoctorRequest;
use Validator;
use App\Doctor;
use App\User;
use App\Patient;
use Auth;

class DoctorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showHomepage()
    {

        $docs = Auth::user()->doctor;
        // dd($items);
        return view('doctors.doctor-home', [
            'docs' => $docs
        ]);

           
    }

    public function index()
    {
        $items = Doctor::with('userInfo')->get();
        // dd($items);
        return view('doctors.list', [
            'items' => $items
        ]);
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('doctors.doctor-form', [
            'orgs' => \App\Organizations::orderBy('organizations')->get()->pluck('organizations', 'id'),
            'affiliations' => \App\Affiliation::orderBy('name')->get()->pluck('name', 'id'),
            'affiliationBranches' => \App\AffiliationBranch::select('name', 'id', 'affiliation_id')->get()->groupBy('affiliation_id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DoctorRequest $request)
    {
        // dd($request->all());
        // get fields for user table
        $input = $request->only([
            'username', 
            'firstname', 
            'lastname',
            'middle_initial',
            'contact_number',
            'birthdate',
            'sex',
            'email',
            'address'
        ]);
        // verify if username exists
        $credentials = $request->only(['username']);


        // assign password: default is firstname+lastname lowercase
        $input['password'] = bcrypt(strtolower($input['firstname']).strtolower($input['lastname']));
        // assign user type
        $input['user_type'] = 'DOCTOR';
        //save to DB (users)
        $user = User::create($input);

        // save to DB (doctors)       
        $doctor = $user->doctor()->create([
            'specialization_id' => $request->specialization,
            // 'clinic' => $request->clinic,
            // 'clinic_address'=> $request->clinic_address,
            // 'clinic_hours' => $request->clinic_hours,
            'ptr' => $request->ptr,
            'prc' => $request->prc,
            's2' => $request->s2,
            'title' => $request->title,
            // 'subspecialty' => $request->subspecialty,
            // 'affiliations' => $request->affiliations,
            'med_school' => $request->med_school,
            'med_school_year' => $request->med_school_year,
            'residency' => $request->residency,
            'residency_year' => $request->residency_year,
            'training' => $request->training,
            'training_year' => $request->training_year,
        ]);

        $doctor->subspecializations()->sync($request->input('subspecializations'));
        $doctor->organizations()->sync($request->input('organizations'));
        
        $affiliations = [];
        foreach(request()->input('affiliations') AS $aff){
            $affiliations[$aff['affiliation_id']] = [
                'affiliation_branch_id' => $aff['branch_id'],
                'clinic_hours' => $aff['clinic_hours'],
            ];
        }
        $doctor->affiliations()->sync($affiliations);
        

       // $user ['subspecialty'] = json_encode($input['subspecialty']);
       return response()->json([
            'url' => route('admin.index') 
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

        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        return view('doctors.edit', [
            'data' => Doctor::whereUserId($id)->with(['userInfo', 'subspecializations', 'organizations', 'affiliations'])->first(),
            'orgs' => \App\Organizations::orderBy('organizations')->get()->pluck('organizations', 'id'),
            'affiliations' => \App\Affiliation::orderBy('name')->get()->pluck('name', 'id'),
            'affiliationBranches' => \App\AffiliationBranch::select('name', 'id', 'affiliation_id')->get()->groupBy('affiliation_id')
        ]);
      }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DoctorRequest $request, $id)
    {
        // get fields for user table
        // dd($request->all());

        $doctor = Doctor::find($id);
        $data = [
            'specialization_id' => $request->specialization,
            'title' => $request->title,
            'med_school' => $request->med_school,
            'med_school_year' => $request->med_school_year,
            'residency' => $request->residency,
            'residency_year' => $request->residency_year,
            'training' => $request->training,
            'training_year' => $request->training_year,
        ];
        if(Auth::user()->isAdmin()){
            $data += [
                'prc' => $request->prc,
                'ptr' => $request->ptr,
                's2' => $request->s2,
            ];
        }
        $doctor->fill($data);
        $doctor->save();

        $doctor->subspecializations()->sync($request->input('subspecializations'));
        $doctor->organizations()->sync($request->input('organizations'));
        
        $affiliations = [];
        foreach(request()->input('affiliations') AS $aff){
            $affiliations[$aff['affiliation_id']] = [
                'affiliation_branch_id' => $aff['branch_id'],
                'clinic_hours' => $aff['clinic_hours'],
            ];
        }
        $doctor->affiliations()->sync($affiliations);

        $user = User::find($doctor->user_id);
        $user->fill($request->only([
            'firstname', 
            'middle_initial',
            'lastname',
            'birthdate',
            'sex',
            'contact_number',
            'address',
            'username', 
            'email',
        ]));
        $user->save();
        
        return response()->json([
            'url' => Auth::user()->isAdmin() ? route('admin.index') : url('/doctor-home') 
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // User::de($id)->delete();
        // return redirect()->route('doctors.index');
    }
}
