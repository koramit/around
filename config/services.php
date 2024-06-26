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

    'SUBHANNAH_API_NAME' => env('SUBHANNAH_API_NAME'),
    'SUBHANNAH_API_TOKEN' => env('SUBHANNAH_API_TOKEN'),
    'SUBHANNAH_API_URL' => env('SUBHANNAH_API_URL'),
    'SUBHANNAH_API_VERIFY' => env('SUBHANNAH_API_VERIFY', true),

    'portal_token' => env('PORTAL_API_TOKEN'),
    'portal_base_url' => env('PORTAL_API_BASE_URL'),
    'portal_verify_ssl' => env('PORTAL_API_VERIFY_SSL', true),
];
