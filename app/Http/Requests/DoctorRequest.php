<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Illuminate\Validation\Rule;
use App\User;
use Illuminate\Contracts\Validation\Validator;

class DoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isAdmin() ||  Auth::user()->isDoctor();
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
            'birthdate' => 'required|date_format:"Y-m-d"',
            'sex' => 'required:in:Male,Female',
            'contact_number' => 'required|min:6',
            'address' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'prc' => 'required',
            'ptr' => 'required',
            // 'specialization' => 'required',
            'title' => 'required',
            // 'clinic' => 'required',
            // 'clinic_address' => 'required',
            // 'clinic_hours' => 'required',
            'med_school' => 'required',
            'med_school_year' => 'required|date_format:"Y"',
            'residency' => 'required',
            'residency_year' => 'required|date_format:"Y"',
            'training' => 'required',
            'training_year' => 'required|date_format:"Y"',
            // 'subspecialty' => 'required',
            // 'affiliations' => 'required',
            'specialization' => 'required|exists:specializations,id',
            'subspecializations' => 'array|required',
            'subspecializations.*' => 'required|exists:subspecializations,id',
            'affiliations' => 'array|required',
            'affiliations.*.affiliation_id' => 'required|exists:affiliations,id',
            'affiliations.*.branch_id' => 'required|exists:affiliation_branches,id',
            'affiliations.*.clinic_hours' => 'required',
            'organizations' => 'array|required',
            'organizations.*' => 'required|exists:organizations,id',
        ];
        // dd($this->route("doctor"));
        if($this->route("doctor"))
        {
            $user_id = \App\Doctor::find($this->route("doctor"))->user_id;
            $rules['username'] = 'required|unique:users,username,'.$user_id;
            $rules['email'] = 'required|unique:users,username,'.$user_id;
            if(Auth::user()->isDoctor()){
                unset($rules['prc'], $rules['ptr'], $rules['s2']);
            }
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
            'prc.required' => 'Please enter your PRC license number.',
            'ptr.required' => 'Please enter your PTR number.',
            // 'specialization.required' => 'Please enter your specialization.',
            'title.required' => 'Please enter your title.',
            'clinic.required' => 'Please enter your clinic name.',
            'clinic_address.required' => 'Please enter your clinic address.',
            'clinic_hours.required' => 'Please enter your clinic hours.',
            'med_school.required' => 'Please enter your med school.',
            'med_school_year.required' => 'Please enter your med school year.',
            'residency.required' => 'Please enter your residency.',
            'residency_year.required' => 'Please enter your residency year.',
            'training.required' => 'Please enter your training.',
            'training_year.required' => 'Please enter your training year.',
            // 'affiliations.required' => 'Please enter your affiliations.',
            'contact_number.min' => 'Please enter valid contact number.'
        ];
    }

    protected function formatErrors(Validator $validator)
    {
        return [
            'errors' => $validator->errors()->all()
        ];
    }
}
