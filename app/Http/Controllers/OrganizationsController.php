<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organizations;

class OrganizationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()


    {

           $items = Organizations::all();
        return view('organizations.organizations', [
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
           $input = $request->only([
            'organizations'
        ]);
       
       Organizations::create($input);


       // return back();
       return redirect()
            ->intended(route('organizations.index'))
            ->with('ACTION_RESULT', [
                'type' => 'success', 
                'message' => 'saved successfully!'
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
        return view('organizations.organizations-edit', [
            'data' => Organizations::find($id)
        ]);
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
         $data = Organizations::find($id);
        $data->fill([
            'organizations' => $request->organizations,
            
        ]);

        $data->save();

         return redirect('/organizations');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       Organizations::destroy($id);
        return redirect()->back();
    }
}
