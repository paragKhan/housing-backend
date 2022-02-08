<?php

namespace App\Http\Requests;

use App\Models\Photo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class StoreApplicationRequest extends FormRequest
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
            'fname' => 'string',
            'lname' => 'string',
            'email' => 'email',
            'nib_no' => 'string',
            'dob' => 'date',
            'phone' => 'string',
            'gender' => 'string',
            'country_of_birth' => 'string',
            'island_of_birth' => 'string',
            'country_of_citizenship' => 'string',
            'house_no' => 'string',
            'street_address' => 'string',
            'po_box' => 'string',
            'island' => 'string',
            'country' => 'string',
            'home_phone' => 'string',
            'passport_no' => 'string',
            'passport_expiry' => 'string',
            'driving_licence_no' => 'string',
            'nib_photo' => 'string|exists:photos,uniqid',
            'passport_photo' => 'string|exists:photos,uniqid',
            'employer' => 'string',
            'industry' => 'string',
            'position' => 'string',
            'work_phone' => 'string',
            'payment_slip' => 'string|exists:photos,uniqid',
        ];
    }

    public function validated()
    {
        $updates = [];

        if ($this->has('nib_photo')) {
            $photo = Photo::where('uniqid', $this->nib_photo)->first();
            $updates = array_merge($updates, ['nib_photo' => $photo->path]);
        }

        if ($this->has('passport_photo')) {
            $photo = Photo::where('uniqid', $this->passport_photo)->first();
            $updates = array_merge($updates, ['passport_photo' => $photo->path]);
        }

        if ($this->has('payment_slip')) {
            $photo = Photo::where('uniqid', $this->payment_slip)->first();
            $updates = array_merge($updates, ['payment_slip' => $photo->path]);
        }

        return array_merge(parent::validated(), $updates);
    }
}
