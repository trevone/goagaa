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

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI'),
        'access_token' => env('FACEBOOK_ACCESS_TOKEN'),
        'base_uri' => env('FACEBOOK_BASE_URI'),
    ],

    'worldnews' => [
        'api_key' => env('WORLD_NEWS_API_KEY'), 
    ],

    'aiml' => [
        'api_key' => env('AIML_API_KEY'),
        'base_uri' => env('AIML_BASE_URI'),
        'text_models' => [
            [
                'key' => 'gpt-4o',
                'desc' => 'GPT-4o (OpenAI)'
            ],
            [
                'key' => 'gpt-4o-mini',
                'desc' => 'GPT-4o-mini (OpenAI)'
            ],
            [
                'key' => 'claude-3-opus-20240229',
                'desc' => 'Claude 3 Opus'
            ],
            [
                'key' => 'claude-3-sonnet-20240229',
                'desc' => 'Claude 3 Sonnet'
            ],
            [
                'key' => 'claude-3-haiku-20240307',
                'desc' => 'Claude 3 Haiku'
            ]
        ],
        'image_models' => [
            [
                'key' => 'prompthero/openjourney',
                'desc' => 'Openjourney v4'
            ],
            [
                'key' => 'SG161222/Realistic_Vision_V3.0_VAE',
                'desc' => 'Realistic Vision 3.0'
            ],
            [
                'key' => 'wavymulder/Analog-Diffusion',
                'desc' => 'Analog Diffusion'
            ],
            [
                'key' => 'flux/schnell',
                'desc' => 'FLUX Schnell'
            ],
            [
                'key' => 'flux-pro',
                'desc' => 'FLUX PRO'
            ],
            [
                'key' => 'flux/dev',
                'desc' => 'FLUX DEV'
            ],
            [
                'key' => 'flux-realism',
                'desc' => 'FLUX Realism LoRA'
            ],
            [
                'key' => 'stable-diffusion-v3-medium',
                'desc' => 'Stable Diffusion 3'
            ],
            [
                'key' => 'dall-e-3',
                'desc' => 'DALLe 3'
            ]
        ]
    ]
];
