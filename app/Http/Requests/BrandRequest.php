<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
            'title'  => 'bail|required',
            'sort'   => 'bail|numeric',
            'images' => 'bail|required',
        ];
    }

    public $attributes = [
        'title'  => '品牌名称',
        'sort'   => '排序',
        'images' => 'LOGO'
    ];
}
