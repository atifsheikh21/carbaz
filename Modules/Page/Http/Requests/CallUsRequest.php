<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallUsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules =  [
            'callus_title' => 'required',
            'callus_header1' => 'required',
            'callus_header2' => 'required',
        ];

        if($this->request->get('lang_code') == admin_lang()){
            $rules['callus_phone'] = 'required';
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
            'callus_header1.required' => trans('translate.Header one is required'),
            'callus_header2.required' => trans('translate.Header two is required'),
            'callus_title.required' => trans('translate.Title is required'),
            'callus_phone.required' =>  trans('translate.Phone is required'),


        ];
    }
}
