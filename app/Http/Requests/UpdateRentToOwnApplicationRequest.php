<?php

namespace App\Http\Requests;

use App\Constants;
use App\Models\RentToOwnApplication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRentToOwnApplicationRequest extends FormRequest
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
            'status' => ['string', Rule::in(Constants::RTO_APPLICATION_STATUSES)],
            'comments' => 'nullable|string'
        ];
    }

    public function validated()
    {
        if($this->rto_application->status == RentToOwnApplication::STATUS_APPROVED && !isAdmin()){
            return [];
        }

        $updates = [];

        if ($this->has('status')
        ) {
            $updates = array_merge($updates, [
                'approvable_type' => get_class(auth()->user()),
                'approvable_id' => auth()->id()
            ]);
        }

        return array_merge(parent::validated(), $updates);
    }
}
