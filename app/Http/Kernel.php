<?php

protected $middlewareGroups = [
    'web' => [
        // other middlewares
    ],

    'api' => [
        \App\Http\Middleware\HandleCors::class,
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];

