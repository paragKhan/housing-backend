<?php

namespace App\Http\Requests;

use App\Models\Photo;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHousingModelRequest extends FormRequest
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
            'gallery' => 'string',
            'description' => 'string',
            'bedrooms' => 'nullable|string',
            'bathrooms' => 'nullable|string',
            'width' => 'nullable|string',
            'garages' => 'nullable|string',
            'patios' => 'nullable|string',
            'master_plan_photo' => 'string',
            'basic_plan_photo' => 'string',
            'include_in_application' => 'boolean'
        ];
    }

    public function validated()
    {
        $updates = [];

        if($this->has('gallery')){
            $uniqids =  explode("|", $this->gallery);
            $updatedGallery = [];

            foreach ($uniqids as $uniqid){
                $photo = Photo::where('uniqid', $uniqid)->first();

                if($photo){
                    array_push($updatedGallery, $photo->path);
                }
            }

            $updates = array_merge($updates, ['gallery' => implode("|", $updatedGallery)]);
        }

        if($this->has('master_plan_photo')){
            $photo = Photo::where('uniqid', $this->master_plan_photo)->first();

            $updates = array_merge($updates, ['master_plan_photo' => $photo->path]);
        }

        if($this->has('basic_plan_photo')){
            $photo = Photo::where('uniqid', $this->basic_plan_photo)->first();

            $updates = array_merge($updates, ['basic_plan_photo' => $photo->path]);
        }

        return array_merge(parent::validated(), $updates);
    }
}
