<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Patient;
use App\Transaction;
use App\TransactionLine;



class TransactionsController extends Controller
{

    protected $patientId;   

    public function __construct(Request $request)
    {
        $this->patientId = $request->patient;
        if(!Patient::whereId($this->patientId)->exists()){
            abort(404); 
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $prescriptions = Patient::whereId($this->patientId)->with(['prescriptions' => function($q){
            
            $q->lacking()->with(['doctor' => function($q){
                $q->select('id', 'user_id')->with(['userInfo' => function($q){
                    $q->select('id', 'firstname', 'middle_initial', 'lastname', 'avatar');
                }]);
            }]);
        }])->first();

        $prescriptions->lacking_prescriptions = $prescriptions->prescriptions->groupBy(function ($item, $key) {
            return $item->doctor->userInfo->fullname();
        });
        unset($prescriptions->lackingPrescriptions);

        return view('transactions.manage', [
            'prescriptions' => $prescriptions
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
            'prescription.*.id' => 'required|exists:prescriptions,id',
            'prescription.*.quantity' => 'required|numeric'
        ]);

        if($v->fails()){
            return response()->json([
                'result' => false,
                'errors' => $v->errors()->all()
            ]);
        }



        $transaction = Transaction::create([
            'patient_id' => $this->patientId,
            'pharmacist_id' => Auth::user()->pharmacist->id
        ]);

        $lines = [];
        foreach($request->input('prescription') AS $val){
            $lines[] = new TransactionLine([
                'prescription_id' => $val['id'],
                'quantity' => $val['quantity']
            ]);
        }
        $transaction->lines()->saveMany($lines);

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
