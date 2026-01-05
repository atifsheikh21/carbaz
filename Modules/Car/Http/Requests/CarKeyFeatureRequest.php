<?php

namespace Modules\Car\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarKeyFeatureRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'seller_type'=>'required',
            'body_type'=>'required',
            'engine_size'=>'required',
            'interior_color'=>'required',
            'exterior_color'=>'required',
            'year'=>'required',
            'mileage'=>'required',
            'number_of_owner'=>'required',
            'fuel_type'=>'required',
            'transmission'=>'required',
            'drive'=>'required',
        ];

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
            'body_type.required' => trans('translate.Body type is required'),
            'engine_size.required' => trans('translate.Engine size is required'),
            'drive.required' => trans('translate.Drive is required'),
            'interior_color.required' => trans('translate.Tnterior color is required'),
            'exterior_color.required' => trans('translate.Exterior color is required'),
            'year.required' => trans('translate.Year is required'),
            'mileage.required' => trans('translate.Mileage is required'),
            'number_of_owner.required' => trans('translate.Number of owner is required'),
            'fuel_type.required' => trans('translate.Fuel type is required'),
            'transmission.required' => trans('translate.Transmission is required'),
            'seller_type.required' => trans('translate.Seller type is required'),
            'purpose.required' => trans('translate.Purpose is required'),
        ];
    }
}
