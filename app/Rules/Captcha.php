<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\GeneralSetting\Entities\GoogleRecaptcha;
use ReCaptcha\ReCaptcha;

class Captcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $google_recaptcha = GoogleRecaptcha::first();
        $recaptcha=new ReCaptcha($google_recaptcha->secret_key);
        $response = $recaptcha->verify($value, $_SERVER['REMOTE_ADDR']);
        if(!$response->isSuccess()){
            $fail('Please complete the recaptcha to submit the form');
        }

    }
}
