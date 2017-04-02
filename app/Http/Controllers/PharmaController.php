<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pharma;
use App\PharmacyManager;
use App\Http\Requests\PharmaRequest;
use App\User;
use Auth;

class PharmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search =  $request->input('search');
        $items = Pharma::where('drugstore', Auth::user()->manager->drugstore);

        if(trim($search)){
            $items->whereHas('userInfo', function($q) USE($search){
                $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE '%{$search}%'");
            });
        }

        return view('pharmacists.list', [
            'items' => $items->get()
        ]);
    }

    public function showHomepage()
    {
        $items = Pharma::with('userInfo')->get();
        // dd($items);
        return view('pharmacists.pharma-home', [
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
         $pman = Auth::user()->manager;
          return view('pharmacists.pharma-form', [
             'pman' => $pman
         ]);

         //return view('pharmacists.pharma-form');

    }

     public function phlist()
    {
        $items = Pharma::with('userInfo')->get();
        // dd($items);
        return view('pharmacists.pharma-home', [
            'items' => $items
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PharmaRequest $request)
    {
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
        $input['user_type'] = 'PHARMA';
        //save to DB (users)
        $user = User::create($input);

        // save to DB (pharmas)       
        $pharmacist = [
            'drugstore' => $request->drugstore,
            'drugstore_address' => $request->drugstore_address,
            'license' => $request->license,
            'user_id' => $user->id
        ];

        Auth::user()->manager->pharmacists()->create($pharmacist);

       return redirect()->route('pharmacists.index');
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
         return view('pharmacists.edit', [
            'data' => Pharma::with('userInfo')->where('user_id', $id)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PharmaRequest $request, $id)
    {
        $pharma = Pharma::find($id);
        $pharma->fill([
            'license' => $request->license,
            'drugstore' => $request->clinic,
            'drugstore_address'=> $request->clinic_address,
        ]);
        $pharma->save();

        $user = User::find($pharma->user_id);
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

       return redirect()->route('pharmacists.index');
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
