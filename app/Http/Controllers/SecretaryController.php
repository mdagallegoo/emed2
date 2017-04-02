<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SecretaryRequest;
use Validator;
use App\Secretary;
use App\User;
use App\Doctor;
use Auth;

class SecretaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showHomepage()
    {

        $items = Secretary::with('userInfo')->get();
        // dd($items);
        return view('secretary.secretary-home', [
            'items' => $items
        ]);

           
    }

    public function index(Request $request)
    {

        $items = Auth::user()->doctor->secretaries();
        $search =  $request->input('search');
        
        
        if(trim($search)){
            $items->whereHas('userInfo', function($q) USE($search){
                $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE '%{$search}%'");
            });
        }

            return view('secretary.list', [
                'items' => $items->get()
            ]);
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('secretary.secretary-form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SecretaryRequest $request)
    {
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
        $input['user_type'] = 'SECRETARY';
        //save to DB (users)
        $user = User::create($input);

        // save to DB (secretaries)       
        $secretary = [
            'attainment' => $request->attainment,
            'user_id' => $user->id
        ];

        Auth::user()->doctor->secretaries()->create($secretary);

        
       // if(Auth::user()->user_type === "DOCTOR")
            return redirect('/doctor-home');
        // else 
        //     return redirect()->route('admin.index');
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
        return view('secretary.edit', [
            'data' => Secretary::with('userInfo')->where('id', $id)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SecretaryRequest $request, $id)
    {
        // get fields for user table

        $secretary = Secretary::find($id);
        $secretary->fill($request->only([
            'attainment' => $request->attainment
        ]));
        $secretary->save();

        $user = User::find($secretary->user_id);
        $user->fill($request->only([
            'username', 
            'firstname', 
            'lastname',
            'middle_initial',
            'contact_number',
            'sex',
            'email',
            'birthdate',
            'address'

        ]));
        $user->save();
        

       return redirect()->route('secretary.index');
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
