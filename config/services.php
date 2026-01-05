<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => '',
    ],

    'facebook' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => '',
    ],

    'motorcheck' => [
        'base_url' => env('MOTORCHECK_BASE_URL'),
        'endpoint' => env('MOTORCHECK_ENDPOINT'),
        'api_key' => env('MOTORCHECK_API_KEY'),
        'registration_param' => env('MOTORCHECK_REG_PARAM'),
        'auth_type' => env('MOTORCHECK_AUTH_TYPE', 'basic'),
        'basic_username' => env('MOTORCHECK_BASIC_USERNAME', env('MOTORCHECK_USERNAME')),
        'basic_password' => env('MOTORCHECK_BASIC_PASSWORD', env('MOTORCHECK_API_KEY')),
        'user_header' => env('MOTORCHECK_USER_HEADER', 'x-username'),
        'user_value' => env('MOTORCHECK_USER_VALUE', env('MOTORCHECK_USERNAME')),
    ],

];
