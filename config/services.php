<?php

return [
    'avant-auth' => [
        'host'                   => env('AUTH_HOST', 'https://auth.avant.one'),
        'redirect'               => env('AUTH_REDIRECT', '/auth/callback'),
        'redirect_authenticated' => env('AUTH_REDIRECT_AUTHENTICATED', '/'),
        'client_id'              => env('AUTH_CLIENT_ID'),
        'client_secret'          => env('AUTH_CLIENT_SECRET'),
    ],
];