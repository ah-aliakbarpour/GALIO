<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogRequest extends FormRequest
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
            'title' => [
                'bail', 'required', 'string', 'max:255',
                Rule::unique('blogs', 'title')->ignore($this->blog),
            ],
            'author' => 'bail|required',
            'context' => 'bail|required',
            'images.*' => 'bail|file|image|mimes:jpg,jpeg,png|max:2048'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'images.*.max' => 'The images must not be greater than 2048 kilobytes.',
            'images.*.image' => 'The uploaded files must be image.',
            'images.*.mimes' => 'The images must be a file of type: jpg, jpeg, png.',
            'images.*.file' => 'Files could not be uploaded.',
        ];
    }
}
