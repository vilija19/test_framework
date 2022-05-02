<?php

namespace Aigletter\Framework;

use Aigletter\Framework\Exceptions\GetComponentException;
use Aigletter\Framework\Interfaces\RunnableInterface;

class Application implements RunnableInterface
{
    protected $config;

    protected static $instance;

    public static function getApp(array $config = [])
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    private function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getComponent($key)
    {
        if (isset($this->config['components'][$key]['factory'])) {
            $factoryClass = $this->config['components'][$key]['factory'];
            $arguments = $this->config['components'][$key]['arguments'] ?? [];
            $factory = new $factoryClass($arguments);
            $instance = $factory->createComponent();
            return $instance;
        }
        /*$class = $this->config['components'][$key]['class'];
        if (class_exists($class)) {
            $instance = new $class();
            return $instance;
        }*/


        throw new GetComponentException('Component not found');
    }

    public function run()
    {
        $router = $this->getComponent('router');
        $action = $router->route($_SERVER['REQUEST_URI']);

        return $action();
    }
}