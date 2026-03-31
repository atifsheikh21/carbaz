<?php

namespace Modules\GeneralSetting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailConfigurationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $host = (string) $this->input('mail_host');
        $port = (int) $this->input('mail_port');

        $isLocalRelay = in_array($host, ['localhost', '127.0.0.1'], true) && $port === 25;

        return [
            'sender_name' => 'required',
            'mail_host' => 'required',
            'email' => 'required',
            'smtp_username' => [Rule::requiredIf(!$isLocalRelay)],
            'smtp_password' => [Rule::requiredIf(!$isLocalRelay)],
            'mail_port' => 'required',
            'mail_encryption' => [Rule::requiredIf(!$isLocalRelay)],
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

    public function messages()
    {
        return [
            'sender_name.required' => trans('translate.Sender name is required'),
            'mail_host.required' => trans('translate.Mail host is required'),
            'email.required' => trans('translate.Email is required'),
            'smtp_username.required' => trans('translate.Smtp username is required'),
            'smtp_password.required' => trans('translate.Smtp password is required'),
            'mail_port.required' => trans('translate.Mail port is required'),
            'mail_encryption.required' => trans('translate.Mail encryption is required'),
        ];
    }
}
