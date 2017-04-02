<?php

namespace App\Validators;
use Hash;
use Auth;

class PasswordMatch
{
    public function check($attribute, $value, $parameters, $validator){

        return Hash::check($value, Auth::user()->password);

    }

}