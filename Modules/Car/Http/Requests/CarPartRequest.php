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

    public function messages(): array
    {
        return [
            'images.max' => __('You can upload maximum 8 images only.'),
            'images.*.uploaded' => __('One of the selected images could not be uploaded. Please remove it, choose a smaller photo, and try again.'),
            'images.*.image' => __('Only image files are allowed.'),
            'images.*.mimes' => __('Images must be JPG, PNG, or WEBP files.'),
            'images.*.max' => __('Each image must be 8MB or smaller.'),
        ];
    }
}
