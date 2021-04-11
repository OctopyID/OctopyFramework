<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

namespace Octopy\HTTP;

use Closure;
use Octopy\HTTP\Middleware\Dispatcher;

class Middleware
{
    /**
     * @var array
     */
    protected $route = [];

    /**
     * @var array
     */
    protected $global = [];

    /**
     * @param  string $property
     * @return array
     */
    public function __get(string $property) : array
    {
        return $this->$property;
    }

    /**
     * @param  string $layer
     * @param  mixed  $middleware
     */
    public function set(string $layer, $middleware = null)
    {
        if (is_null($middleware)) {
            if (! isset($this->global[$layer])) {
                $this->global[] = $layer;
            }
        } else if (! isset($this->route[$layer])) {
            $this->route[$layer] = $middleware;
        }
    }

    /**
     * @param  string $layer
     * @return mixed
     */
    public function route($layer = null)
    {
        if (is_null($layer)) {
            return $this->route;
        }

        if (! is_string($layer)) {
            return $layer;
        }

        return $this->route[$layer] ?? $layer;
    }

    /**
     * @return array
     */
    public function global() : array
    {
        return $this->global ?? [];
    }

    /**
     * @param  array   $middleware
     * @param  Request $object
     * @param  Closure $next
     * @return Closure
     */
    public function dispatch(array $middleware, Request $object, Closure $next)
    {
        return (new Dispatcher($middleware))->dispatch($object, $next);
    }
}
