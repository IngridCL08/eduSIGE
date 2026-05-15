<?php

return [

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],

        'aspirante' => [
            'driver'   => 'session',
            'provider' => 'aspirantes',
        ],

        'alumno' => [
            'driver'   => 'session',
            'provider' => 'alumnos',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],

        'aspirantes' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Aspirante::class,
        ],

        'alumnos' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Alumno::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
