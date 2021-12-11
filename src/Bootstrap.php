<?php

namespace App;



use Exception;
use Spyc;
use Steampixel\Route;

class Bootstrap
{
    private Route $routing;
    private array $routes;

    public function __construct()
    {
        $this->routing = new Route();
        $this->routes = Spyc::YAMLLoad(project_root.'/config/routes.yaml');
        $env = Spyc::YAMLLoad(project_root.'/config/env.yaml');
        foreach ($env as $key => $value)
        {
            $_ENV[$key] = $value;
        }
    }

    /**
     * @param $class
     * @param $method
     * @param array $mandatory
     * @return mixed
     * @throws Exception
     */
    private function runControllerMethod($class, $method, array $mandatory = array())
    {
        if (!class_exists($class)) {
            throw new Exception('Class not found.');
        }
        $class = new $class;
        if(!method_exists($class,$method)) {
            throw new Exception('Method not found.');
        }
        return call_user_func_array(array($class, $method), $mandatory);
    }

    /**
     * @throws Exception
     */
    private function setNotFoundController()
    {
        if(!isset($_ENV['NOTFOUND_CONTROLLER']))
        {
            throw new Exception('NOTFOUND_CONTROLLER: is not set in env.yaml');
        }
        return $classFQN = $_ENV['NOTFOUND_CONTROLLER'];
    }

    public function addRoutes()
    {
        foreach ($this->routes as $route)
        {
                $this->routing->add($route['expression'], function () use ($route) {
                    $arguments = func_get_args();
                    try {
                        return $this->runControllerMethod($route['controller'], $route['method'], $arguments);
                    } catch (Exception $e) {
                        return 'Exception abgefangen: '. $e->getMessage() . "\n";
                    }
                }, $route['request']);
        }
    }

    public function addNotFound()
    {
        $this->routing->pathNotFound(function() {
            header('HTTP/1.0 404 Not Found');
            try {
                $controller = $this->setNotFoundController();
                echo $this->runControllerMethod($controller,'notFound',func_get_args());
            } catch (Exception $e) {
                echo 'Exception abgefangen: '. $e->getMessage() . "\n";
            }
        });
    }

    public function init()
    {
        $this->routing->run('/');
    }

}
