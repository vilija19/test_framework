<?php

return [
    'app_name' => 'Test framework',
    'components' => [
        'router' => [
            //'class' => \Aigletter\Framework\Components\Router::class,
            'factory' => \Aigletter\Framework\Components\Routing\RouterFactory::class,
        ],
        'cache' => [
            //'class' => \Aigletter\Framework\Components\Cache::class,
            'factory' => \Aigletter\Framework\Components\Caching\CacheFactory::class,
            'arguments' => [
                'filename' => __DIR__ . '/../data/cache/cache.json'
            ]
        ],
        'test' => [
            'factory' => \Aigletter\App\Components\Test\TestFactory::class,
        ]
    ]
];