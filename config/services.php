<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'briva' => [
        'production' => [
            "client_id" => "VsHgJYElKYBLPRQGfevmX7OvuoGs35HL",
            "secret_id" => "SxLAVMxDLWzNTsGl",
            'institutionCode' => "ALPCR7697K7",
            'url' => "https://partner.api.bri.co.id",
            'coorporate_code' => "12605"
        ],
        'sandbox' => [
            "client_id" => "MtrslSsfJGIOWXIuhol3Jw0iGv2qcbEc",
            "secret_id" => "edNlUdDXzRXA9InW",
            'institutionCode' => "J104408",
            'url' => "https://sandbox.partner.api.bri.co.id",
            'coorporate_code' => "77777"
        ]
    ]

];
