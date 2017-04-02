<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PharmacyManager;
use App\Http\Requests\ManagerRequest;
use App\User;
use Auth;

class ManagersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transaction()
    {   
        return view('pharmacists.pharma-transaction');
    }

    public function index()
    {
        $items = PharmacyManager::with('userInfo')->get();
        // dd($items);
        return view('managers.list', [
            'items' => $items
        ]);
    }

    public function showHomepage()
    {

       $items = Auth::user()->manager;
        // dd($items);
        return view('managers.pmanager-home', [
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
         return view('managers.manager-form');
    }

     public function phlist()
    {
        $items = PharmacyManager::with('userInfo')->get();
        // dd($items);
        return view('pharmacists.list', [
            'items' => $items
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManagerRequest $request)
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
        $input['user_type'] = 'PMANAGER';
        //save to DB (users)
        $user = User::create($input);

        // save to DB (managers)       
        $user->manager()->create([
            'drugstore' => $request->drugstore,
            'drugstore_branch' => $request->drugstore_branch,
            'license' => $request->license
        ]);

       return redirect()->route('admin.index');
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
         return view('managers.edit', [
            'data' => PharmacyManager::with('userInfo')->where('user_id', $id)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ManagerRequest $request, $id)
    {
         $manager = PharmacyManager::find($id);
        $manager->fill([
            'license' => $request->license,
            'drugstore' => $request->drugstore,
            'drugstore_branch'=> $request->drugstore_branch,
        ]);
        $manager->save();

        $user = User::find($manager->user_id);
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
        

       return redirect()->route('admin.index');
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
