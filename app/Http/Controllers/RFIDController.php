<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;

class RFIDController extends Controller
{
    public function scan(Request $request)
    {
        $v = Validator::make($request->all(), [
            'uid' => 'required|alpha_num'
        ]);

        if($v->fails()) {
            return response()->json([
                'result' => false,
                'errors' => $v->errors()->all()
            ]);
        }

        $user = User::whereUid($request->uid)->first();
        if(!$user){
            return response()->json([
                'result' => false,
                'errors' => ['Tag not recognized!']
            ]);
        }

        return response()->json([
            'result' => true,
            'url' => route('patients.show', ['id' => $user->patient->id])
        ]);

        
    }
}
