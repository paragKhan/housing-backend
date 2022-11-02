<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRentToOwnApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fname' => 'required|string',
            'lname' => 'required|string',
            'nib_no' => 'required|string',
            'email' => 'required|email',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'country_of_birth' => 'required|string',
            'island_of_birth' => 'required|string',
            'country_of_citizenship' => 'required|string',
            'phone' => 'required|string',
            'passport_photo' => 'required|mimes:jpeg,jpg,png,pdf|exclude',
            'nib_photo' => 'required|mimes:jpeg,jpg,png,pdf|exclude',
            'job_letter_document' => 'required|mimes:jpeg,jpg,png,pdf|exclude',
            'paystub_photo' => 'required|mimes:jpeg,jpg,png,pdf|exclude',
            'credit_facilities' => 'required|mimes:jpeg,jpg,png,pdf|exclude',
        ];
    }
}
