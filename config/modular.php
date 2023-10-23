<?php

declare(strict_types=1);


return [
    'path' => base_path() . '/app/Modules',
    'base_namespace' => base_path() . 'App\Modules',
    'groupWithoutPrefix' => 'Login',

    'groupMidleware' => [
        'Admin' => [
            'web' => ['auth'],
            'api' => ['auth.api']
        ]
    ],

    'modules' =>[
        'Admin' => [
            'User'
        ],
        'Auth' =>[
            'Login',
            'Register',
        ],
    ]

];
