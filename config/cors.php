<?php

return [

    // Aplica CORS a estas rutas (nuestras APIs)
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // Métodos permitidos
    'allowed_methods' => ['*'],

    // Orígenes permitidos (localhost, 127.0.0.1 y emulador Android)
    'allowed_origins' => [
        'http://localhost',
        'http://localhost:*',
        'http://127.0.0.1',
        'http://127.0.0.1:*',
        'http://10.0.2.2',
        'http://10.0.2.2:*', // Emulador Android
    ],

    'allowed_origins_patterns' => [],

    // Headers permitidos
    'allowed_headers' => ['*'],

    // Headers expuestos (si necesitas alguno, añádelo aquí)
    'exposed_headers' => [],

    'max_age' => 0,

    // Si vas a usar cookies/sesión cruzada, pon true. Para API con tokens, false.
    'supports_credentials' => false,
];
