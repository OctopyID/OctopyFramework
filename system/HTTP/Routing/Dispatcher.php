<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : www.octopy.xyz
 * @license : MIT
 */

namespace Octopy\HTTP\Routing;

use Closure;
use ReflectionMethod;
use ReflectionFunction;

use Octopy\Application;
use Octopy\Support\Arr;
use Octopy\HTTP\Request;
use Octopy\HTTP\Middleware;
use Octopy\HTTP\Routing\Route;

class Dispatcher
{
    /**
     * @var Octopy\Application
     */
    protected $app;
    
    /**
     * @var Octopy\Routing\Route
     */
    protected $route;
    
    /**
     * @param Application $app
     * @param Request     $request
     * @param Route       $route
     */
    public function __construct(Application $app, Route $route)
    {
        $this->app = $app;
        $this->route = $route;
    }

    /**
     * @return Response
     */
    public function run()
    {
        // parameter
        $parameter = array_reverse(Arr::where($this->route->parameter, function ($array) {
            return !is_null($array);
        }));

        // middleware
        $middleware = $this->route->middleware;

        $method = '';
        if (is_string($controller = $this->route->controller)) {
            [$class, $method] = explode('@', $controller, 2);

            $parameter = $this->class($parameter, $controller = $this->app->make($class), $method);

            $this->middleware($middleware, $controller, $method);
        }

        // layer
        $next = function () use ($controller, $method, $parameter) {
            if (!$controller instanceof Closure) {
                $response = $controller->$method(...array_values($parameter));
            } else {
                $response = $controller(...array_values($this->method($parameter, new ReflectionFunction($controller))));
            }

            return $this->app['response']->make($response);
        };

        return $this->app['middleware']->dispatch($middleware, $this->app['request'], $next);
    }

    /**
     * @param  array  &$middleware
     * @param  object $controller
     * @param  string $method
     * @return void
     */
    protected function middleware(array &$middleware, $controller, string $method)
    {
        if (!method_exists($controller, 'middleware')) {
            return;
        }

        foreach ($controller->middleware() as $layer) {
            if (isset($layer['option']['only']) && !in_array($method, $layer['option']['only'])) {
                continue;
            }

            if (isset($layer['option']['except']) && in_array($method, $layer['option']['except'])) {
                continue;
            }

            $middleware[] = $layer['middleware'];
        }
    }

    /**
     * @param  array  $parameter
     * @param  object $instance
     * @param  string $method
     * @return array
     */
    protected function class(array $parameter, $instance, string $method)
    {
        if (!method_exists($instance, $method)) {
            return $parameter;
        }

        return $this->method($parameter, new ReflectionMethod($instance, $method));
    }

    /**
     * @param  array   $parameter
     * @param  unknown $reflector
     * @return array
     */
    public function method(array $parameter, $reflector) : array
    {
        $count = 0;

        $array = array_values($parameter);

        foreach ($reflector->getParameters() as $key => $dependency) {
            $instance = $this->transform($dependency, $parameter);

            if (!is_null($instance)) {
                $count++;
                $this->splice($parameter, $key, $instance);
            } elseif (!isset($array[$key - $count]) && $dependency->isDefaultValueAvailable()) {
                $this->splice($parameter, $key, $dependency->getDefaultValue());
            }
        }

        return $parameter;
    }

    /**
     * @param  unknown $dependency
     * @param  array   $parameter
     * @return mixed
     */
    protected function transform($dependency, array $parameter)
    {
        $class = $dependency->getClass();

        if ($class && !$this->already($class->name, $parameter)) {
            return $dependency->isDefaultValueAvailable() ? $dependency->getDefaultValue() : $this->app->make($class->name);
        }
    }

    /**
     * @param  string $class
     * @param  array  $parameter
     * @return bool
     */
    protected function already($class, array $parameter)
    {
        return !is_null(Arr::first($parameter, function ($array) use ($class) {
            return $array instanceof $class;
        }));
    }

    /**
     * @param  array  $parameter
     * @param  string $offset
     * @param  mixed  $array
     * @return void
     */
    protected function splice(array &$parameter, $offset, $array)
    {
        array_splice($parameter, $offset, 0, [$array]);
    }
}
