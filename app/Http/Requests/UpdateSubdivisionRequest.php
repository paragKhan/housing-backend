<?php

namespace App\Http\Requests;

use App\Models\Photo;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubdivisionRequest extends FormRequest
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
            'heading' => 'string',
            'location' => 'string',
            'description' => 'string',
            'photo' => 'string|exists:photos,uniqid'
        ];
    }

    public function validated()
    {
        $updates = [];

        if ($this->has('photo')) {
            $photo = Photo::where('uniqid', $this->photo)->first();
            $updates = array_merge($updates, ['photo' => $photo->path]);
        }

        return array_merge(parent::validated(), $updates);
    }
}
