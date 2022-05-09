<?php

namespace Aigletter\App\Components\ZanRouter;

use Aigletter\Contracts\ComponentFactory;

class ZanRouterFactory extends ComponentFactory
{
    protected function createConcreteComponent()
    {
        return new \Vilija19\Router\Router($this->arguments['routes']);
    }
}