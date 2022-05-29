<?php
/**
 * тестовый роутер для ДЗ 15
 * 
 * @author Alex <billibons777@gmail.com>
 * @version 1.0
 * 
 * Сорри за использование Aigletter в неймспейсе - только чтоб автозагрузка работала
 */

namespace Aigletter\App\Components\ZanRouter;

/**
 * Класс  Router
 */
class Router implements \Aigletter\Contracts\Routing\RouteInterface
{
    /**
     * Свойство хранящее значение типа array.
     * Этот массив хранит соответствие роута вызываемым обьектам (например контроллерам)
     * examples:
     *  '/home/index' => [ \Aigletter\App\Controllers\HomeController::class , 'index'],
     *  '/product/show'=> function () { echo('Run callback'); }
     * @var array
     * @access protected
     */
    protected $routes;
    /**
     * Свойство хранящее значение типа обьект
     * Здесь храним эксемпляр контроллера, который будет обрабатывать роут
     * @var Object
     * @access protected
     */
    protected $controller;
    /**
     * Свойство хранящее значение типа строка
     * Здесь храним название метода контроллера, который будет обрабатывать роут
     * @var string
     * @access protected
     */    
    protected $method;
    /**
     * Свойство хранящее вызываемый объект.
     * @var callable
     * @access protected
     */
    protected $outObj;
    /**
     * Свойство хранящее строку с параметрами для вызываемого метода.
     * @var string
     * @access protected
     */
    protected $parameters = '';
    /**
     * Свойство хранит массив ошибок
     * @var array 
     * @access protected
     */
    protected $errors;


    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
        /**
         * Роут notfound нужен для работы данного роутера.
         * Если его нет - добавляем.
         */
        if (!isset($routes['/notfound'])) {
            $this->addRoute('/notfound', function ($messages=[]) { echo('Page not found <br>');
                foreach ($messages as $message) {
                    echo($message . '<br>');
                }
            });
        }
    }

    /**
     * Этот метод реализовывет интерфейс RouteInterface из пакета aigletter/contracts 
     * @param string $uri  - роут.
     * @return callable 
     */    
    public function route(string $uri): callable
    {
        $routeData = explode('?',$uri); // $uri - "/product/view?anything=test&id=123"
        $route = $routeData[0]; 
        
        /**
         * добавим роут на несуществующий метод контроллера для демонстрации 
         * исключения HttpMethodNotAllowedException CODE 405
         * route - product/view2?anything=test&id=123
         * (Только для демонстрации)
         */
        $this->addRoute('/product/view2', [ \Aigletter\App\Controllers\ProductController::class , 'view2']);
        /**
         * добавим роут на несуществующий callback контроллера для демонстрации 
         * исключения Exception CODE 500 
         * route - product/show2
         * (Только для демонстрации)
         */
        $this->addRoute('/product/show2', 'test');
        
        try {
            if (isset($this->routes[$route]) && is_array($this->routes[$route])) {

                $actionInfo = $this->routes[$route];
    
                if (class_exists($actionInfo[0])) {
                    $this->controller = new $actionInfo[0];
                }else{
                    throw new HttpNotFoundException("Error. Router class not found", 404);
                }  
    
                if (method_exists($this->controller, $actionInfo[1])) {
                    $this->method = $actionInfo[1];
                }else{
                    throw new HttpMethodNotAllowedException("Error. Router method not found", 405);
                }
    
                $this->outObj = [$this->controller, $this->method];
    
            }elseif (isset($this->routes[$route]) && is_callable($this->routes[$route])) {
    
                $this->outObj = $this->routes[$route];
    
            }else{
                if (isset($this->routes[$route])) {
                    throw new \Exception("Fatal error", 500);
                }else{
                    throw new HttpNotFoundException("Error. Route not found", 404);
                }
            }

        } catch (HttpMethodNotAllowedException $e) {
            http_response_code($e->getCode());
            echo $e->getMessage() . '. Generated by HttpMethodNotAllowedException';
            exit();
        } catch (HttpNotFoundException $e) {
            http_response_code($e->getCode());
            echo $e->getMessage() . '. Generated by HttpNotFoundException';
            exit();
        } catch (HttpException $e) {
            http_response_code($e->getCode());
            echo $e->getMessage();
            exit();            
        } catch (\Exception $e) {
            http_response_code(500);
            echo $e->getMessage();
            exit();
        }


        return function () {
            return call_user_func($this->outObj,12);
        };

    }

    /**
     * Этот метод определяет какие входные параметры нужны для метода контроллера. 
     * И пытается получить их из HTTP запроса. 
     * Если не находит обязательных параметров вызывется страница /notfound с мообщением об ошибке
     * @return bool . false если нет всех обязательных параметров
     */
    protected function reflections():bool
    {
        $isEnought = true;
        $parametersArray = array();
        $inspectMethod = new \ReflectionMethod($this->controller::class,$this->method);
        $parameters = $inspectMethod->getParameters();
        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            try {
                $defaultValue = $parameter->getDefaultValue();
            } catch (\Throwable $th) {
                $defaultValue = false;
            }
            $type = $parameter->getType();
            $typeName = $type->getName();

            if (isset($_GET[$name])) {
                $param = $_GET[$name];
            }elseif (isset($_POST[$name])) {
                $param = $_GET[$name];
            }elseif ($defaultValue) {
                $param = '';
            }else{
                $this->errors[] = "Not all requared parameters has been provided";
                $isEnought = false;
                break;
            }
            $parametersArray[] = $param;
        }
        $this->parameters = implode(',',$parametersArray);
        return $isEnought;
    }

    /**
     * Этот метод дает возможность добавлять обработчики роутов
     * @var string $path - роут.
     * @var callable $action - обработчик
     */
    public function addRoute(string $path, $action)
    {
        if ($path && $action) {
            $this->routes[$path] = $action;
        }
    }

}