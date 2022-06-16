<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'image' => ['bail', 'required', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'first_header' => 'bail|required',
            'second_header' => 'bail|required',
            'paragraph' => 'bail|required',
            'link' => 'bail|required',
        ];

        //  If editing, the image isn't required
        if ($this->input('submit') == 'Edit')
            unset($rules['image'][1]);  // Delete second item of array ('required')

        return $rules;
    }
}
