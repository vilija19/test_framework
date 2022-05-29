<?php

return [
    'app_name' => 'Test framework',
    'components' => [
        'router' => [
            'factory' => \Aigletter\Framework\Components\Routing\RouterFactory::class,
        ],
        'cache' => [
            'factory' => \Aigletter\Framework\Components\Caching\CacheFactory::class,
            'arguments' => [
                'filename' => __DIR__ . '/../data/cache/cache.json'
            ]
        ],
        'test' => [
            'factory' => \Aigletter\App\Components\Test\TestFactory::class,
        ],
        'zanrouter' => [
            'factory' => \Aigletter\App\Components\ZanRouter\ZanRouterFactory::class,
            'arguments' => [
                'routes' => array(
                    '/'            => function () { echo('This is homepage'); },
                    '/home/index' => [ \Aigletter\App\Controllers\HomeController::class , 'index'],
                    '/page/view' => [ \Aigletter\App\Controllers\ShopController::class , 'show'],
                    '/user/login' => [ \Aigletter\App\Controllers\UserController::class , 'login'],
                    '/product/view' => [ \Aigletter\App\Controllers\ProductController::class , 'view'],
                    '/product/show'=> function () { echo('Run callback'); }
                )
            ]            
        ]        
    ]
];