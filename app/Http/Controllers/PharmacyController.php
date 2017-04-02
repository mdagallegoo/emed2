<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pharmacy AS Pharmacy;
use App\PharmacyBranch AS Branch;
use Validator;

class PharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pharmacy.list', [
            'items' => Pharmacy::with('branches')->orderBy('name', 'DESC')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pharmacy = new Pharmacy;
        $pharmacy->branches = [new Branch];
        return view('pharmacy.manage', [
            'data' => $pharmacy
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
        $v = Validator::make($request->all(), [
            'name' => 'required|unique:pharmacies',
            'branch' => 'required|array',
            'branch.*.name' => 'required',
            'branch.*.address' => 'required',
        ]);

        if($v->fails()){
            return response()->json([
                'result' => false,
                'errors' => $v->errors()->all()
            ]);
        }

        $pharmacy = Pharmacy::create([
            'name' => $request->input('name')
        ]);

        $branches = [];
        foreach($request->input('branch') AS $branch){
            $branches[] = new Branch([
                'name' => $branch['name'],
                'address' => $branch['address']
            ]);
        }

        $pharmacy->branches()->saveMany($branches);

        return response()->json([
            'result' => true
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
        return view('pharmacy.manage', [
            'data' => Pharmacy::with('branches')->whereId($id)->first() 
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
        $v = Validator::make($request->all(), [
            'name' => "required|unique:pharmacies,name,{$id}",
            'branch' => 'required|array',
            'branch.*.name' => 'required',
            'branch.*.address' => 'required',
            'branch.*.id' => 'exists:pharmacy_branches,id',
        ]);

        if($v->fails()){
            return response()->json([
                'result' => false,
                'errors' => $v->errors()->all()
            ]);
        }

        $pharmacy = Pharmacy::find($id);
        $pharmacy->name = $request->input('name');
        $pharmacy->save();

        $branches = [];
        $existing = [];
        foreach($request->input('branch') AS $branch){
            $fields = ['name' => $branch['name'], 'address' => $branch['address']];
            if(isset($branch['id'])){
                $existing[] = $branch['id'];
                Branch::whereId($branch['id'])->update($fields);
            }else{
                $branches[] = new Branch($fields);
            }
        }

        if(!empty($existing)){
            Branch::wherePharmacyId($id)->whereNotIn('id', $existing)->delete();
        }else{
            Branch::wherePharmacyId($id)->delete();
        }

        if(!empty($branches)){
            $pharmacy->branches()->saveMany($branches);
        }

        return response()->json([
            'result' => true
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
        //
        Pharmacy::destroy($id);
        return redirect()->back();
    }
}
