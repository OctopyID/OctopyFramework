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

namespace Octopy\HTTP\Middleware;

use Closure;

use Octopy\Support\App;
use Octopy\HTTP\Request;

class Dispatcher
{
    /**
     * @var array
     */
    protected $middleware;

    /**
     * @param array $middleware
     */
    public function __construct(array $middleware = [])
    {
        $this->middleware = $middleware;
    }

    /**
     * @param  array   $middleware
     * @param  Request $object
     * @param  Closure $next
     * @return mixed
     */
    public function dispatch(Request $object, Closure $next)
    {
        $middleware = array_reverse($this->middleware);

        $complete = array_reduce($middleware, function (Closure $next, $middleware) {
            return $this->create($next, $middleware);
        }, $this->next($next));

        return $complete($object);
    }

    /**
     * @param  Closure $next
     * @return Closure
     */
    protected function next(Closure $next)
    {
        return function ($object) use ($next) {
            return $next($object);
        };
    }

    /**
     * @param  Closure  $next
     * @param  callable $middleware
     * @return mixed
     */
    protected function create(Closure $next, $middleware)
    {
        return function ($object) use ($next, $middleware) {
            if ($middleware instanceof Closure) {
                return $middleware($object, $next);
            }
            
            return App::make($middleware)->handle($object, $next);
        };
    }
}
