<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Captcha;
use App\Models\User;
use Illuminate\Validation\Rule;
use Modules\Country\Entities\Country;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $isDealer = $this->input('user_type') === 'dealer';
        $isVehicleSeller = (string) $this->input('is_vehicle_seller') === '1';
        $isPartSeller = (string) $this->input('is_part_seller') === '1';

        $ireland = Country::where('code', 'IE')->orWhere('name', 'Ireland')->first();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:4', 'max:100'],
            'user_type' => ['required', 'in:dealer,individual'],
            'city_id' => [
                'required',
                'integer',
                Rule::exists('cities', 'id')->where(function ($query) use ($ireland) {
                    if ($ireland?->id) {
                        $query->where('country_id', $ireland->id);
                    } else {
                        $query->whereRaw('1=0');
                    }
                }),
            ],
            'is_vehicle_seller' => $isDealer ? ['required', 'in:0,1'] : ['nullable', 'in:0,1'],
            'is_part_seller' => $isDealer ? ['required', 'in:0,1'] : ['nullable', 'in:0,1'],

            'vehicle_company_name' => ($isDealer && $isVehicleSeller) ? ['required', 'string', 'max:255'] : ['nullable', 'string', 'max:255'],
            'vehicle_company_address' => ($isDealer && $isVehicleSeller) ? ['required', 'string', 'max:220'] : ['nullable', 'string', 'max:220'],

            'part_company_name' => ($isDealer && $isPartSeller) ? ['required', 'string', 'max:255'] : ['nullable', 'string', 'max:255'],
            'part_company_address' => ($isDealer && $isPartSeller) ? ['required', 'string', 'max:220'] : ['nullable', 'string', 'max:220'],

            'g-recaptcha-response'=>new Captcha()
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $isDealer = $this->input('user_type') === 'dealer';
            if (!$isDealer) {
                return;
            }

            $isVehicleSeller = (string) $this->input('is_vehicle_seller') === '1';
            $isPartSeller = (string) $this->input('is_part_seller') === '1';

            if (!$isVehicleSeller && !$isPartSeller) {
                $validator->errors()->add('seller_modules', trans('translate.Please select at least one selling option'));
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required' => trans('translate.Name is required'),
            'email.required' => trans('translate.Email is required'),
            'email.unique' => trans('translate.Email already exist'),
            'password.required' => trans('translate.Password is required'),
            'password.confirmed' => trans('translate.Confirm password does not match'),
            'password.min' => trans('translate.You have to provide minimum 4 character password'),
            'user_type.required' => trans('translate.User Type is required'),
            'city_id.required' => trans('translate.City is required'),
            'is_vehicle_seller.required' => trans('translate.This field is required'),
            'is_part_seller.required' => trans('translate.This field is required'),
            'vehicle_company_name.required' => trans('translate.Company name is required'),
            'vehicle_company_address.required' => trans('translate.Address is required'),
            'part_company_name.required' => trans('translate.Company name is required'),
            'part_company_address.required' => trans('translate.Address is required'),
        ];
    }
}
