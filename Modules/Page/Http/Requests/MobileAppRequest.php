<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MobileAppRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'short_title' => 'required',
            'mobile_title' => 'required',
            'mobile_description' => 'required',
        ];

        if($this->request->get('lang_code') == admin_lang()){
            $rules['mobile_playstore'] = 'required';
            $rules['mobile_appstore'] = 'required';
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
            'short_title.required' => trans('translate.Short title is required'),
            'mobile_title.required' => trans('translate.Title is required'),
            'mobile_description.required' => trans('translate.Description is required'),
            'mobile_playstore.required' => trans('translate.Play store link is required'),
            'mobile_appstore.required' => trans('translate.App store link is is required'),
        ];
    }
}
