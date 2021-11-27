<?php

namespace App;



use Spyc;
use Steampixel\Route;

class Bootstrap
{
    public Route $routing;
    public array $routes;

    public function __construct()
    {
        $this->routing = new Route();
        $this->routes = Spyc::YAMLLoad(project_root.'/config/routes.yaml');
    }

    public function addRoutes()
    {
        foreach ($this->routes as $route)
        {

            if($route['value'])
            {
                $this->routing->add($route['expression'], function ($id) use ($route) {
                    $controller = false;
                    $method = false;
                    extract($route);
                    if (class_exists($controller)) {
                        $class = new $controller;
                    } else {
                        throw new \Exception('Class not found');
                    }
                    return $class->$method($id);
                }, $route['request']);
            } else {
                $this->routing->add($route['expression'], function () use ($route) {
                    $controller = false;
                    $method = false;
                    extract($route);
                    if (class_exists($controller)) {
                        $class = new $controller;
                    } else {
                        throw new \Exception('Class not found');
                    }
                    return $class->$method();
                }, $route['request']);
            }

        }
    }

    public function init()
    {
        $this->routing->run('/');
    }

}