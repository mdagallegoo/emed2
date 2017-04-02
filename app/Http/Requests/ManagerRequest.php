<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Illuminate\Validation\Rule;
use App\User;

class ManagerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'firstname' => 'required',
            'middle_initial' => 'required|size:1',
            'lastname' => 'required',
            'birthdate' => 'required',
            'sex' => 'required',
            'contact_number' => 'required|min:6',
            'address' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'drugstore' => 'required',
            'drugstore_branch' => 'required',
            'license' => 'required',
        ];

        if($this->isMethod('post')){
            $rules['username'] = 'required:unique:users';
            $rules['email'] = 'required:unique:users';
        }else{
            // dd($this->route('doctor'));
            $rules['username'] = [
                'required',
                 Rule::unique('users')->ignore($this->input('user_id'))
            ];
              $rules['email'] = [
                'required',
                 Rule::unique('users')->ignore($this->input('user_id'))
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'firstname.required' => 'Please enter your first name.',
            'middle_initial.required' => 'Please enter your middle initial.',
            'lastname.required' => 'Please enter your last name.',
            'birthdate.required' => 'Please enter your birthdate.',
            'sex.required' => 'Please enter your gender.',
            'contact_number.required' => 'Please enter your contact number.',
            'address.required' => 'Please enter your home address.',
            'username.required' => 'Please enter your username.',
            'email.required' => 'Please enter your email.',
            'drugstore.required' => 'Please enter your drusgtore.',
            'drugstore_branch.required' => 'Please enter your drusgtore address.',
            'license.required' => 'Please enter your license.',
            'email.unique' => 'Email already taken',
             'username.unique' => 'Taken username.',
             'contact_number.min' => 'Please enter valid contact number.'
        ];
    }

}
