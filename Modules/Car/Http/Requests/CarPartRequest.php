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
                'brand_id' => 'nullable|integer',
                'city_id' => 'required|exists:cities,id',
                'condition' => 'required|string|max:50',
                'regular_price' => 'required|numeric',
                'part_number' => 'nullable|string|max:255',
                'compatibility' => 'nullable|string|max:255',
                'description' => 'required|string',
                'images' => 'required|array|min:1',
                'images.*' => 'nullable|mimes:jpg,png,webp,jpeg|max:5120',
            ];
        }

        if ($this->isMethod('put')) {
            return [
                'title' => 'required|string|max:255',
                'brand_id' => 'nullable|integer',
                'city_id' => 'required|exists:cities,id',
                'condition' => 'required|string|max:50',
                'regular_price' => 'required|numeric',
                'part_number' => 'nullable|string|max:255',
                'compatibility' => 'nullable|string|max:255',
                'description' => 'required|string',
                'images' => 'nullable|array',
                'images.*' => 'nullable|mimes:jpg,png,webp,jpeg|max:5120',
            ];
        }

        return [];
    }

    public function authorize()
    {
        return true;
    }
}
