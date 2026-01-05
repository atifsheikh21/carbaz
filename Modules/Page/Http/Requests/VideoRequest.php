<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
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
            'video_title' => 'required',
        ];

        if($this->request->get('lang_code') == admin_lang()){
            $rules['video_id'] = 'required';

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
            'video_title.required' => trans('translate.Title is required'),
            'video_id.required' => trans('translate.Video id is required'),
        ];
    }
}
