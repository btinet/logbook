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
     * @param $mandatory
     * @return mixed
     * @throws Exception
     */
    private function runControllerMethod($class, $method, $mandatory = null)
    {
        if (!class_exists($class)) {
            throw new Exception('Class not found.');
        } else {
            $class = new $class;
            if(!method_exists($class,$method)) {
                throw new Exception('Method not found.');
            } else {
                return $class->$method($mandatory);
            }
        }
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
        $this->routing->pathNotFound(function($path) {
            header('HTTP/1.0 404 Not Found');
            try {
                echo $this->runControllerMethod('App\Controller\NotFoundController','index',$path);
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