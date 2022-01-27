<?php

return [
	/*
    |--------------------------------------------------------------------------
    | Defined your Constant here
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for stattic value as needed.
    |
    |
    */

    'SEND_ERROR_REPORT_TO' => 'anand.esprit@gmail.com',
    'MAX_EMAIL_ATTEMPT' => 5,
    'DEFAULT_USER_AVATAR_ID' => 3,

    'EMAIL_TEMPLATE' => [
        'ADMIN' => [
            'FORGOT_PASSWORD' => 1,
            'RESET_PASSWORD' => 2,
        ],
    ],

    'ROLE' => [
        'ADMIN' => 1,
    ],

    'JOB' => [
        'TYPE' => [
        ]
    ]
];