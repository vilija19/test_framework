<?php

namespace Aigletter\Framework\Components\Routing;

use Aigletter\Framework\Contracts\ComponentFactoryAbstract;

class RouterFactory extends ComponentFactoryAbstract
{
    protected function createConcreteComponent()
    {
        return new Router();
    }
}