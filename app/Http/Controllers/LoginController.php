<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    // public function __construct()
    // {
    //  $this->middleware()
    //  if(Auth::check()){
    //      return redirect('/');
    //  }   
    // }


    public function showLoginPage()
    {
        return view('login');
    }

    public function doLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|exists:users',
            'password' => 'required',
        ], [
            'username.exists' => 'Your username is not registered!',
            // 'username.required' => 'Ayaw i empty!'
        ]);

        $credentials = $request->only(['username', 'password']);
        if(Auth::attempt($credentials)){

            $user = Auth::user();
            if($user->user_type === 'ADMIN')
            {
                return redirect('/admin');
            }
            else if($user->user_type === 'DOCTOR')
            {
                return redirect('/doctor-home'); //test

            }else if($user->user_type === 'PMANAGER'){

                return redirect('/pmanager-home'); //test

            }else if($user->user_type === 'PATIENT'){

                return redirect('/patient-home'); //test

            }else if($user->user_type === 'SECRETARY'){

                return redirect('/secretary-home'); //test

            }else if($user->user_type === 'PHARMA'){

                return redirect('/pharmacists-home');

        }
 }

        else{
              return view('login', [
                'wrongPassword' => 'Incorrect Password'
            ]);
        }
       
    }
}

