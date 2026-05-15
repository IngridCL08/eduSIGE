<?php

return [
    'private_key'     => env('CONEKTA_PRIVATE_KEY', ''),
    'public_key'      => env('CONEKTA_PUBLIC_KEY', ''),
    'webhook_secret'  => env('CONEKTA_WEBHOOK_SECRET', ''),
    'api_version'     => env('CONEKTA_API_VERSION', '2.0.0'),
    'locale'          => 'es',
    'currency'        => 'MXN',
];
