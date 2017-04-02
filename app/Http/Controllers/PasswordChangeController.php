<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\User;

class PasswordChangeController extends Controller
{


    public function showHomepage()
    {

        return view('partials.ChangePass');

           
    }

    public function postUpdatePassword(Request $request) {

        $user = Auth::user();

        $password = $request->only([
            'current_password', 'new_password', 'new_password_confirmation'
        ]);

        $validator = Validator::make($password, [
            'current_password' => 'required|current_password_match',
            'new_password'     => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/|confirmed',

            ]);

        if ( $validator->fails() )
            return back()
                ->withErrors($validator)
                ->withInput();


        $updated = $user->update([ 'password' => bcrypt($password['new_password']) ]);


        if($updated)
                if(Auth::user()->user_type === "DOCTOR")
        {
           return redirect('/doctor-home')->with('ACTION_RESULT', [
                'type' => 'success', 
                'message' => 'Password change successful!'
            ]);
        return redirect('/doctor-home')->with('success', 0);

        }
         else if(Auth::user()->user_type === "PATIENT")
        {
           return redirect('/patient-home')->with('ACTION_RESULT', [
                'type' => 'success', 
                'message' => 'Password change successful!'
            ]);

        return redirect('/patient-home')->with('success', 0);

        }
        else if(Auth::user()->user_type === "SECRETARY")
        {
           return redirect('/secretary-home')->with('ACTION_RESULT', [
                'type' => 'success', 
                'message' => 'Password change successful!'
            ]);

        return redirect('/secretary-home')->with('success', 0);

        }
         else if(Auth::user()->user_type === "PMANAGER")
        {
           return redirect('/pmanager-home')->with('ACTION_RESULT', [
                'type' => 'success', 
                'message' => 'Password change successful!'
            ]);
        return redirect('/pmanager-home')->with('success', 0);

        }
            
    }


}
