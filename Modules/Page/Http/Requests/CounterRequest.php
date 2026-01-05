<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CounterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules =  [
            'counter_title1' => 'required',
            'counter_title2' => 'required',
            'counter_title3' => 'required',
        ];

        if($this->request->get('lang_code') == admin_lang()){
            $rules['counter_qty1'] = 'required';
            $rules['counter_qty2'] = 'required';
            $rules['counter_qty3'] = 'required';
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'counter_qty1.required' => trans('translate.Quantity is required'),
            'counter_title1.required' =>  trans('translate.Title is required'),
            'counter_qty2.required' => trans('translate.Quantity is required'),
            'counter_title2.required' =>  trans('translate.Title is required'),
            'counter_qty3.required' => trans('translate.Quantity is required'),
            'counter_title3.required' =>  trans('translate.Title is required'),


        ];
    }
}
