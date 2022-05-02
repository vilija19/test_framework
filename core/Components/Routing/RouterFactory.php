<?php

namespace Aigletter\Framework\Components\Routing;

use Aigletter\Contracts\ComponentFactory;

class RouterFactory extends ComponentFactory
{
    protected function createConcreteComponent()
    {
        return new Router();
    }
}