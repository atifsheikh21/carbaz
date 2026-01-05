<?php

namespace Modules\Car\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarAddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('post')) {
            $rules = [
                'city_id'=>'required|exists:cities,id',
                'country_id'=>'required|exists:countries,id',
                'address'=>'required',
                'google_map'=>'required',
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
            'agent_id.required' => trans('translate.Dealer is required'),
            'brand_id.required' => trans('translate.Brand is required'),
            'city_id.required' => trans('translate.City is required'),
            'country_id.required' => trans('translate.Country is required'),
            'title.required' => trans('translate.Title is required'),
            'slug.required' => trans('translate.Slug is required'),
            'slug.unique' => trans('translate.Slug already exist'),
            'description.required' => trans('translate.Description is required'),
            'condition.required' => trans('translate.Condition is required'),
            'regular_price.required' => trans('translate.Regular price is required'),
            'regular_price.numeric' => trans('translate.Regular price should be numeric'),
            'offer_price.numeric' => trans('translate.Offer price should be numeric'),
            'address.required' => trans('translate.Address is required'),
            'google_map.required' => trans('translate.Google map is required'),
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
            'thumb_image.required' => trans('translate.Image is required'),
            'purpose.required' => trans('translate.Purpose is required'),
        ];
    }
}
