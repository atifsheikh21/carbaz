<?php

namespace Modules\Car\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarPartRequest extends FormRequest
{
    public function rules()
    {
        if ($this->isMethod('post')) {
            return [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'brand_id' => 'nullable|string|max:255',
                'car_model' => 'nullable|string|max:255',
                'from_year' => 'nullable|integer|min:1900|max:2100',
                'to_year' => 'nullable|integer|min:1900|max:2100',
                'warranty_months' => 'nullable|integer|min:1|max:144',
                'city_id' => 'required|exists:cities,id',
                'condition' => 'required|string|max:50',
                'regular_price' => 'required|numeric',
                'part_number' => 'nullable|string|max:255',
                'images' => 'nullable|array|max:8',
                'images.*' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:8192',
            ];
        }

        if ($this->isMethod('put')) {
            return [
                'title' => 'required|string|max:255',
                'brand_id' => 'nullable|string|max:255',
                'car_model' => 'nullable|string|max:255',
                'from_year' => 'nullable|integer|min:1900|max:2100',
                'to_year' => 'nullable|integer|min:1900|max:2100',
                'warranty_months' => 'nullable|integer|min:1|max:144',
                'city_id' => 'required|exists:cities,id',
                'condition' => 'required|string|max:50',
                'regular_price' => 'required|numeric',
                'part_number' => 'nullable|string|max:255',
                'description' => 'required|string',
                'images' => 'nullable|array|max:8',
                'images.*' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:8192',
            ];
        }

        return [];
    }

    public function authorize()
    {
        return true;
    }
}
