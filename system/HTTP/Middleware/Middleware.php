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

namespace Octopy\HTTP;

use Closure;

use Octopy\HTTP\Middleware\Dispatcher;

class Middleware
{
    /**
     * @var array
     */
    public $route = [];

    /**
     * @var array
     */
    public $global = [];

    /**
     * @param string $layer
     * @param mixed  $middleware
     */
    public function set(string $layer, $middleware = null)
    {
        if (is_null($middleware)) {
            if (!isset($this->global[$layer])) {
                $this->global[] = $layer;
            }
        } elseif (!isset($this->route[$layer])) {
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

        if (!is_string($layer)) {
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

    public function dispatch(array $middleware = [], Request $object, Closure $next)
    {
        return (new Dispatcher($middleware))->dispatch($object, $next);
    }
}
