<?php

namespace Aigletter\Framework\Components\Caching;

use Aigletter\Framework\Contracts\ComponentFactoryAbstract;

class CacheFactory extends ComponentFactoryAbstract
{
    protected function createConcreteComponent()
    {
        return new Cache($this->arguments['filename']);
    }
}