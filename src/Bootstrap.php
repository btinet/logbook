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
            if($route['value'])
            {
                $this->routing->add($route['expression'], function ($id) use ($route) {
                    try {
                        return $this->runControllerMethod($route['controller'], $route['method'], $id);
                    } catch (Exception $e) {
                        return 'Exception abgefangen: '. $e->getMessage() . "\n";
                    }
                }, $route['request']);
            } else {
                $this->routing->add($route['expression'], function () use ($route) {
                    try {
                        return $this->runControllerMethod($route['controller'], $route['method']);
                    } catch (Exception $e) {
                        return 'Exception abgefangen: '. $e->getMessage() . "\n";
                    }
                }, $route['request']);
            }
        }
    }

    public function addNotFound()
    {
        $this->routing->pathNotFound(function($path) {
            // Do not forget to send a status header back to the client
            // The router will not send any headers by default
            // So you will have the full flexibility to handle this case
            header('HTTP/1.0 404 Not Found');
            echo 'Error 404 :-(<br>';
            echo 'The requested path "'.$path.'" was not found!';
        });
    }

    public function init()
    {
        $this->routing->run('/');
    }

}