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

use Countable;
use ArrayIterator;
use IteratorAggregate;

use Octopy\HTTP\Request;
use Octopy\HTTP\Middleware;
use Octopy\HTTP\Routing\Exception\RouteNotFoundException;
use Octopy\HTTP\Routing\Exception\MethodNotAllowedException;

class Collection implements Countable, IteratorAggregate
{
    /**
     * @var array
     */
    protected $route = [];

    /**
     * @var array
     */
    protected $alias = [];

    /**
     * @param Middleware $middleware
     */
    public function __construct(Middleware $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * @param Route $route
     */
    public function set(Route $route)
    {
        foreach ($route->method as $method) {
            $this->route[$method][$route->uri] = $route;
        }

        return $route;
    }

    /**
     * @param  string $method
     * @return array
     */
    public function get(string $method) : array
    {
        return $this->route[$method] ?? [];
    }

    /**
     * @return void
     */
    public function refresh()
    {
        foreach ($this->route as $array) {
            foreach ($array as $route) {
                if ($route->name && !isset($this->alias[$route->name])) {
                    $this->alias[$route->name] = $route;
                }

                foreach ($route->middleware as $offset => $layer) {
                    $route->data['middleware'][$offset] = $this->middleware->route($layer);
                }
            }
        }
    }
    
    /**
     * @param  Request $request
     * @return Response
     */
    public function match(Request $request)
    {
        $route = $this->get(
            $method = $request->method()
        );

        if (($match = $this->search($route, $path = $request->path())) === false) {
            foreach (array_diff(['POST', 'GET'], [$method]) as $method) {
                $route = $this->get($method);
                if (($match = $this->search($route, $path)) !== false) {
                    break;
                }
            }
        }

        if ($match instanceof Route) {
            if (!in_array($request->method(), $match->method())) {
                throw new MethodNotAllowedException;
            }

            return $match;
        }
        
        throw new RouteNotFoundException;
    }

    /**
     * @param  array  $route
     * @param  string $path
     * @return mixed
     */
    protected function search(array $route, string $request)
    {
        if (isset($route[$request])) {
            return $route[$request];
        }
        
        foreach ($route as $uri => $route) {
            if (preg_match($route->pattern, $request, $parameter)) {
                return $route->parameter(array_filter($parameter, function ($value, $key) {
                    return is_string($key);
                }, ARRAY_FILTER_USE_BOTH));
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function all() : array
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function alias() : array
    {
        return $this->alias;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->route);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->route);
    }
}
