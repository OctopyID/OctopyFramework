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

use Octopy\Application;
use Octopy\HTTP\Request;
use Octopy\HTTP\Middleware;
use Octopy\HTTP\Routing\Route;
use Octopy\HTTP\Routing\Collection;
use Octopy\HTTP\Routing\Dispatcher;

class Router
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $group;

    /**
     * @var Octopy\HTTP\Routing\Collection
     */
    protected $collection;

    /**
     * @var Octopy\HTTP\Middleware
     */
    protected $middleware;

    /**
     * @param Application $app
     * @param Collection  $collection
     * @param Compiler    $compiler
     */
    public function __construct(Application $app, Collection $collection, Middleware $middleware, Compiler $compiler)
    {
        $this->app = $app;
        $this->compiler = $compiler;
        $this->collection = $collection;
        $this->middleware = $middleware;
    }

    /**
     * @param  string $property
     * @return mixed
     */
    public function __get(string $property)
    {
        return $this->$property;
    }

    /**
     * @param  mixed $route
     * @return void
     */
    public function load($route) : void
    {
        if ($route instanceof Collection) {
            $this->collection = $route;
        } else {
            require $this->app['path']->app->route($route);
        }
    }

    /**
     * @param array   $attribute
     * @param Closure $callback
     */
    public function group(array $attribute, Closure $callback)
    {
        foreach (['prefix', 'namespace', 'middleware'] as $attrname) {
            if (isset($attribute[$attrname])) {
                if ($attrname === 'middleware') {
                    $attribute[$attrname] = (array)$attribute[$attrname];
                }

                $this->group[$attrname][] = $attribute[$attrname];
            }
        }

        $callback($this);

        foreach (['prefix', 'namespace', 'middleware'] as $attrname) {
            if (isset($attribute[$attrname])) {
                array_pop($this->group[$attrname]);
            }
        }
    }

    /**
     * @param string  $namespace
     * @param Closure $callback
     */
    public function namespace(string $namespace, Closure $callback)
    {
        $this->group(['namespace' => $namespace], $callback);
    }

    /**
     * @param string  $prefix
     * @param Closure $callback
     */
    public function prefix(string $prefix, Closure $callback)
    {
        $this->group(['prefix' => $prefix], $callback);
    }

    /**
     * @param string  $middleware
     * @param Closure $callback
     */
    public function middleware(string $middleware, Closure $callback)
    {
        $this->group(['middleware' => $middleware], $callback);
    }

    /**
     * @param  string   $uri
     * @param  callable $controller
     * @return Route
     */
    public function get(string $uri, $controller)
    {
        return $this->set(['GET'], $uri, $controller);
    }

    /**
     * @param  string   $uri
     * @param  callable $controller
     * @return Route
     */
    public function post(string $uri, $controller)
    {
        return $this->set(['POST'], $uri, $controller);
    }

    /**
     * @param  string   $uri
     * @param  callable $controller
     * @return Route
     */
    public function any(string $uri, $controller)
    {
        return $this->set(['GET', 'POST'], $uri, $controller);
    }

    /**
     * @return array
     */
    public function all() : array
    {
        return $this->collection->all();
    }

    /**
     * @param array    $method
     * @param string   $uri
     * @param callable $controller
     */
    public function set(array $method, string $uri, $controller)
    {
        // Method
        $method = array_map('strtoupper', $method);

        // URI
        if (isset($this->group['prefix'])) {
            $uri = implode(DS, $this->group['prefix']) . DS . $uri;
        }

        $uri = $this->normalize($uri);

        if ($uri !== DS) {
            $uri = rtrim($uri, DS);
        }

        if (substr($uri, 0, 1) !== DS) {
            $uri = DS . $uri;
        }

        // Controller
        if (is_string($controller)) {
            if (isset($this->group['namespace'])) {
                $controller = BS . implode(BS, $this->group['namespace']) . BS . $controller;
            }

            $controller = explode('@', $controller);
            if (!isset($controller[1])) {
                $controller[1] = '__invoke';
            }

            $controller = implode('@', $controller);
        }

        $controller = $this->normalize($controller);

        // Middleware
        $middleware = $this->middleware->global();

        if (isset($this->group['middleware'])) {
            foreach (end($this->group['middleware']) as $layer) {
                array_push($middleware, $this->normalize(
                    $this->middleware->route($layer)
                ));
            }
        }

        // Parse URI to get to pattern & optional parameter
        [$pattern, $parameter] = $this->compiler->parse($uri);

        return $this->collection->set(new Route(get_defined_vars()));
    }

    /**
     * @param  Request $request
     * @return Response
     */
    public function dispatch(Request $request)
    {
        $route = $this->collection->match($request);

        try {
            $response = $this->app->make(Dispatcher::class, [
                'app'   => $this->app,
                'route' => $route,
            ])->run();
        } catch (Exception $exception) {
        }

        return $this->app['response']->make($response);
    }

    /**
     * @param  mixed $value
     * @return mixed
     */
    protected function normalize($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        return preg_replace('/\/+/', DS, preg_replace('/\\\\+/', BS, $value));
    }
}
