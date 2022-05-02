<?php

namespace Aigletter\App\Components\Test;

use Aigletter\Framework\Contracts\ComponentFactoryAbstract;

class TestFactory extends ComponentFactoryAbstract
{
    protected function createConcreteComponent()
    {
        return new Test();
    }
}