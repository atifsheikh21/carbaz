<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutUsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'header'=>'required',
            'title'=>'required',
            'description'=>'required',
            'total_car'=>'required',
            'total_car_title'=>'required',
            'total_review'=>'required',
            'total_review_title'=>'required',
        ];
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
            'header.required' => trans('translate.Header is required'),
            'title.required' => trans('translate.Title is required'),
            'description.required' => trans('translate.Description is required'),
            'total_car.required' => trans('translate.Total car is required'),
            'total_car_title.required' => trans('translate.Total car title is required'),
            'total_review.required' => trans('translate.Total review is required'),
            'total_review_title.required' => trans('translate.Total review title is required'),
        ];
    }
}
