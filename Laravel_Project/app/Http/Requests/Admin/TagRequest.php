<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class TagRequest extends FormRequest
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
        // Define type using route name
        // Type can be 'product' or 'blog'
        // (route names are like 'admin.product.tag...' or 'admin.blog.tag...')
        try {
            $type = explode('.', Route::currentRouteName())[1];
        }
        catch (\Exception $exception) {
            abort(404);
        }
        finally {
            if ($type !== 'product' && $type !== 'blog')
                abort(404);
        }

        return [
            'title' => [
                'bail', 'required', 'string', 'max:255',
                Rule::unique('tags', 'title')
                    ->ignore($this->tag)
                    ->where('type', $type),  // Unique on 'title' & 'type' columns
            ],
        ];
    }
}
