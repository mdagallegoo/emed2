<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Doctor;

// use App\Doctor;

class AdminController extends Controller
{

	public function index(Request $request)
	{
 		$search =  $request->input('search');
 		$type = $request->input('user_type');

 		$items = User::select();

 		if(trim($search)){
 			$items->whereRaw("CONCAT(firstname, ' ', lastname) LIKE '%{$search}%'");
 		}
 		if(in_array($type, ['DOCTOR','PMANAGER','PATIENT','PHARMA','SECRETARY'])){
 			$items->whereUserType($type);
 		}

        return view('admin.adminhome', [
        	'items' => $items->get()
    	]);

	}

	// public function edit($id)
	// {
	// 	return view('admin.edit-doc', [
	// 		'data' => Doctor::with('userInfo')->where('id', $id)->first()
	// 	]);
	// }
}



        //      $patients = Auth::user()->doctor->patients()->paginate(6);
        // return view('patients.list', [
        //     'patients' => $patients
        //     ]);
