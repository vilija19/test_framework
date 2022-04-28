<?php

return [
    'app_name' => 'Test framework',
    'components' => [
        'router' => [
            'class' => \Aigletter\Framework\Components\Router::class,
        ],
        'cache' => [
            'class' => \Aigletter\Framework\Components\Cache::class,
        ],
    ]
];