<?php

namespace Modules\Car\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
                'agent_id'=>'required',
                'brand_id'=>'required',
                'city_id'=>'required',
                'country_id'=>'required',
                'title'=>'required',
                'slug'=>'required|unique:cars',
                'description'=>'required',
                'condition'=>'required',
                'regular_price'=>'required|numeric',
                'offer_price'=> 'nullable|numeric',
                'address'=>'required',
                'google_map'=>'required',
                'body_type'=>'required',
                'engine_size'=>'required',
                'drive'=>'nullable|string|max:255',
                'interior_color'=>'nullable|string|max:255',
                'exterior_color'=>'required',
                'year'=>'required',
                'mileage'=>'required',
                'number_of_owner'=>'required',
                'fuel_type'=>'required',
                'transmission'=>'required',
                'seller_type'=>'required',
                'thumb_image'=>'required|mimes:jpg,png',
                'gallery_images' => 'nullable|array',
                'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'motorcheck_reg' => 'nullable|string|max:50',
                'motorcheck_make' => 'nullable|string|max:255',
                'motorcheck_model' => 'nullable|string|max:255',
                'motorcheck_version' => 'nullable|string|max:255',
                'motorcheck_body' => 'nullable|string|max:255',
                'motorcheck_doors' => 'nullable|integer',
                'motorcheck_reg_date' => 'nullable|date',
                'motorcheck_engine_cc' => 'nullable|integer',
                'motorcheck_colour' => 'nullable|string|max:255',
                'motorcheck_fuel' => 'nullable|string|max:255',
                'motorcheck_transmission' => 'nullable|string|max:255',
                'motorcheck_no_of_owners' => 'nullable|integer',
                'motorcheck_tax_class' => 'nullable|string|max:50',
                'motorcheck_tax_expiry_date' => 'nullable|date',
                'motorcheck_nct_expiry_date' => 'nullable|date',
                'motorcheck_co2_emissions' => 'nullable|integer',
                'motorcheck_last_date_of_sale' => 'nullable|date',
                'motorcheck_raw' => 'nullable|string',
                'purpose'=>'required|in:Sale',
            ];
        }

        if ($this->isMethod('put')) {
            if($this->request->get('lang_code') == admin_lang()){
                $rules = [
                    'agent_id'=>'required',
                    'brand_id'=>'required',
                    'city_id'=>'required',
                    'country_id'=>'required',
                    'title'=>'required',
                    'slug'=>'required|unique:cars,slug,'.$this->car.',id',
                    'description'=>'required',
                    'condition'=>'required',
                    'regular_price'=>'required|numeric',
                    'offer_price'=> 'nullable|numeric',
                    'address'=>'required',
                    'google_map'=>'required',
                    'body_type'=>'required',
                    'engine_size'=>'required',
                    'drive'=>'nullable|string|max:255',
                    'interior_color'=>'nullable|string|max:255',
                    'exterior_color'=>'required',
                    'year'=>'required',
                    'mileage'=>'required',
                    'number_of_owner'=>'required',
                    'fuel_type'=>'required',
                    'transmission'=>'required',
                    'seller_type'=>'required',
                    'thumb_image'=>'required|mimes:jpg,png',
                    'motorcheck_reg' => 'nullable|string|max:50',
                    'motorcheck_make' => 'nullable|string|max:255',
                    'motorcheck_model' => 'nullable|string|max:255',
                    'motorcheck_version' => 'nullable|string|max:255',
                    'motorcheck_body' => 'nullable|string|max:255',
                    'motorcheck_doors' => 'nullable|integer',
                    'motorcheck_reg_date' => 'nullable|date',
                    'motorcheck_engine_cc' => 'nullable|integer',
                    'motorcheck_colour' => 'nullable|string|max:255',
                    'motorcheck_fuel' => 'nullable|string|max:255',
                    'motorcheck_transmission' => 'nullable|string|max:255',
                    'motorcheck_no_of_owners' => 'nullable|integer',
                    'motorcheck_tax_class' => 'nullable|string|max:50',
                    'motorcheck_tax_expiry_date' => 'nullable|date',
                    'motorcheck_nct_expiry_date' => 'nullable|date',
                    'motorcheck_co2_emissions' => 'nullable|integer',
                    'motorcheck_last_date_of_sale' => 'nullable|date',
                    'motorcheck_raw' => 'nullable|string',
                    'purpose'=>'required|in:Sale',
                ];
            }else{
                $rules = [
                    'title'=>'required',
                    'description'=>'required',
                    'address'=>'required',
                ];
            }
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
