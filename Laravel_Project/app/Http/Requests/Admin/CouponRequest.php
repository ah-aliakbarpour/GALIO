<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
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
            'code' => [
                'bail', 'required', 'string', 'max:255',
                Rule::unique('coupons', 'code')->ignore($this->coupon),
            ],
            'price' => 'bail|required|numeric|between:0.01,999999999.99',
        ];
    }
}
