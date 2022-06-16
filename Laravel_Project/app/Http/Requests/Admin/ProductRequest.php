<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
                Rule::unique('products', 'title')->ignore($this->product),
            ],
            'stock' => 'bail|required|integer|between:0,1000000000',
            'price' => 'bail|required|numeric|between:0,999999999.99',
            'discount' => 'bail|nullable|integer|between:1,100',
            'images.*' => 'bail|file|image|mimes:jpg,jpeg,png|max:2048',
            'information.*.*' => 'bail|required',
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
