<?php

namespace Aigletter\Framework\Components;

use Aigletter\Framework\Interfaces\RouteInterface;

class Router implements RouteInterface
{
    public function route(string $uri): callable
    {
        return function () use ($uri) {
            if ($uri === '/') {
                echo 'Главная страница';
            } else {
                $segments = explode("/", $uri);

                array_shift($segments);

                if ($segments[0] === 'shop' && is_numeric($segments[1])) {
                    echo 'Этот запрос мы умеем обрабатывать. ' . $segments[0] . '-' . $segments[1];
                } else {
                    http_response_code(404);
                    echo '404 Not found';
                }
            }
        };
    }
}