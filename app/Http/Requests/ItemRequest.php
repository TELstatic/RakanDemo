<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
            'sku'      => 'bail|required',
            'module'   => 'bail|required',
            'standard' => 'bail|required',
            'price'    => 'bail|required',
            'reserve'  => 'bail|required',
        ];
    }

    public $attributes = [
        'sku'      => '单品SKU',
        'module'   => '单品型号',
        'standard' => '单品规格',
        'price'    => '单品价格',
        'reserve'  => '单品库存',
    ];

}
