<?php

return [
    'name'     => env('APP_NAME', 'eduSIGE'),
    'env'      => env('APP_ENV', 'production'),
    'debug'    => (bool) env('APP_DEBUG', false),
    'url'      => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'America/Mexico_City'),
    'locale'   => env('APP_LOCALE', 'es'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'es'),
    'faker_locale'    => env('APP_FAKER_LOCALE', 'es_MX'),
    'cipher'   => 'AES-256-CBC',
    'key'      => env('APP_KEY'),
    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store'  => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    // Configuración personalizada de eduSIGE
    'edusige' => [
        'institucion'         => env('EDUSIGE_INSTITUCION', 'Tecnm Pinotepa'),
        'monto_ficha'         => (float) env('EDUSIGE_MONTO_FICHA', 500.00),
        'dias_vigencia_ficha' => (int) env('EDUSIGE_DIAS_VIGENCIA_FICHA', 5),
        'logo'                => env('EDUSIGE_LOGO', null),
    ],
];
