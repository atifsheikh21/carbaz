<?php

namespace Modules\Testimonial\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'designation' => 'required',
            'comment' => 'required',
        ];

        if ($this->isMethod('post')) {
            $rules['image'] ='required';
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
            'name.required' => trans('translate.Name is required'),
            'designation.required' => trans('translate.Designation is required'),
            'image.required' => trans('translate.Image is required'),
            'comment.required' => trans('translate.Comment is required'),
        ];
    }
}
