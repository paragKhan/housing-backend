<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
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
            'title' => 'string',
            'body' => 'string',
            'is_popular' => 'string',
            'photo' => 'mimes:jpeg,jpg,png|exclude'
        ];
    }

    public function validated()
    {
        return array_merge(parent::validated(), [
            'is_popular' => $this->is_popular === "true"
        ]);
    }
}
