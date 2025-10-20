<?php

namespace Alyssoncpc\QueryGenerator\Domain;

use Alyssoncpc\QueryGenerator\Exceptions\NotFoundException;

class Router {

    static protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    public static function get($uri, $callback, $middleware = [])
    {
        self::$routes['GET'][$uri] = [
            'callback' => $callback,
            'middlewares' => $middleware,
        ]; 
    }

    public static function post($uri, $callback, $middleware = [])
    {
        self::$routes['POST'][$uri] = [
            'callback' => $callback,
            'middlewares' => $middleware,
        ]; 
    }

    public static function getRoutes()
    {
        return self::$routes;
    }

    public static function checkUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset(self::$routes[$method][$uri]))
            throw new NotFoundException();
        $route = self::$routes[$method][$uri];

        foreach ($route['middlewares'] as $middleware)
            call_user_func($middleware);

        return call_user_func($route['callback']);
    }

}