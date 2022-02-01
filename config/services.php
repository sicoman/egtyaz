<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('APP_URL').'/auth/login/google/callback',
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID','424616721471197'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET','3af10fbe908eb93d358e01d2bd971332'),
        'redirect' => env('APP_URL').'auth/login/facebook/callback',
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID', 'oo2fSOidkfbCEznKPp4XJ2LZq'),
        'client_secret' => env('TWITTER_CLIENT_SECRET','228gSogFoj089fwYCJIgTZk6UGmu1sFTANRNSXFGvKyK0UPuSk'),
        'redirect' => env('APP_URL').'auth/login/twitter/callback',
    ],

    'zendesk' => array(
        'subdomain' => 'momaiz',
        'username' => 'momaiz.net@gmail.com',
        'token' => 'jQj9HPDPEFJqkYHXsZzKq5xaI4fCHEXxyfBGa39Z'
    ),

];
