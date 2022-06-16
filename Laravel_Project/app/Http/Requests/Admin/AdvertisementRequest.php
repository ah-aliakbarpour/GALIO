<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdvertisementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'position' => [
                'bail', 'required',
                Rule::in([1, 2, 3, 4, 5, 6]),
                Rule::unique('advertisements', 'position')->ignore($this->advertisement),
            ],
            'image_' . $this->input('position') => ['bail', 'required', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'link_' . $this->input('position') => 'bail|required',
        ];

        //  If editing, the image isn't required
        if ($this->input('submit') == 'Edit')
            unset($rules['image_' . $this->input('position')][1]);  // Delete second item of array ('required')

        return $rules;
    }
}
