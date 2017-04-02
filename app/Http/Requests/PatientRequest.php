<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Illuminate\Validation\Rule;
use App\User;

class PatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->doctor();
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
            'email' => 'required|email|unique:users',
            'bloodtype' => 'required',
            'enumber' => 'required',
            'erelationship' => 'required',
            'econtact' => 'required',
            'civilstatus' => 'required',
            'nationality' => 'required',
            'occupation' => 'required',
            'allergyname' => 'present|required_if:allergyquestion,Y',
            'allergyquestion' => 'present|in:Y,N',
            'past_disease' => 'present',
            'past_surgery' => 'present',
            'family_history' => 'present',
            'immunization' => 'present'
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
            'bloodtype.required' => 'Please enter your bloodtype.',
            'nationality.required' => 'Please enter your nationality.',
            'civilstatus.required' => 'Please enter your civilstatus.',
            'econtact.required' => 'Please enter your emergency contact.',
            'erelationship.required' => 'Please enter your relationship with emergency contact.',
            'enumber.required' => 'Please enter your Emergency person contact number.',
            'occupation.required' => 'Please enter your occupation.',
            'email.unique' => 'Email already taken',
            'username.unique' => 'Taken username.',
            'allergyname.unique' => 'Input allergy name.',
            'contact_number.min' => 'Please enter valid contact number.'
            
        ];
    }
}


