<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->request->get('lang_code') == admin_lang()){
            $rules = [
                'email' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'map_code' => 'required',
                'title' => 'required',
                'description' => 'required',
            ];
        }else{
            $rules = [
                'address' => 'required',
                'title' => 'required',
                'description' => 'required',
            ];
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
            'email.required' => trans('translate.Email is required'),
            'phone.required' => trans('translate.Phone is required'),
            'address.required' => trans('translate.Address is required'),
            'map_code.required' => trans('translate.Google Map is required'),
            'title.required' => trans('translate.Title is required'),
            'description.required' => trans('translate.Description is required'),
        ];
    }
}
