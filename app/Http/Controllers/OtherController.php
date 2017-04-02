<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtherController extends Controller
{
    public function aboutus()
    {
        // potang ina mo
        return view('others.aboutus');
    }

    public function contactus()
    {
        // potang ina mo
        return view('others.contactus');
    }

        public function faq()
    {
        // potang ina mo
        return view('others.faq');
    }

   

    
}