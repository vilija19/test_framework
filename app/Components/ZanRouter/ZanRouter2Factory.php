<?php

namespace Aigletter\App\Components\ZanRouter;

use Aigletter\Contracts\ComponentFactory;

class ZanRouter2Factory extends ComponentFactory
{
    protected function createConcreteComponent()
    {
        return new \Aigletter\App\Components\ZanRouter\Router($this->arguments['routes']);
    }
}