<?php

namespace Aigletter\Framework;

use Aigletter\Framework\Exceptions\GetComponentException;
use Aigletter\Framework\Interfaces\RunnableInterface;

class Application implements RunnableInterface
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getComponent($key)
    {
        if (isset($this->config['components'][$key]['class'])) {
            $class = $this->config['components'][$key]['class'];
            if (class_exists($class)) {
                $instance = new $class();
                return $instance;
            }
        }

        throw new GetComponentException('Component not found');
    }

    public function run()
    {
        $router = $this->getComponent('router');
        $action = $router->route($_SERVER['REQUEST_URI']);

        return $action();
    }
}