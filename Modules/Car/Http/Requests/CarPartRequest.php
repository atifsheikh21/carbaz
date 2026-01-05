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
                'slug' => 'required|string|max:255|unique:car_parts,slug',
                'brand_id' => 'nullable|integer',
                'condition' => 'required|string|max:50',
                'regular_price' => 'required|numeric',
                'offer_price' => $this->request->get('offer_price') ? 'numeric' : '',
                'part_number' => 'nullable|string|max:255',
                'compatibility' => 'nullable|string|max:255',
                'description' => 'required|string',
                'thumb_image' => 'nullable|mimes:jpg,png,webp,jpeg',
            ];
        }

        if ($this->isMethod('put')) {
            return [
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:car_parts,slug,' . $this->car_part . ',id',
                'brand_id' => 'nullable|integer',
                'condition' => 'required|string|max:50',
                'regular_price' => 'required|numeric',
                'offer_price' => $this->request->get('offer_price') ? 'numeric' : '',
                'part_number' => 'nullable|string|max:255',
                'compatibility' => 'nullable|string|max:255',
                'description' => 'required|string',
                'thumb_image' => 'nullable|mimes:jpg,png,webp,jpeg',
            ];
        }

        return [];
    }

    public function authorize()
    {
        return true;
    }
}
