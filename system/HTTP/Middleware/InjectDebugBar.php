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
use Throwable;

use Octopy\Application;
use Octopy\HTTP\Request;
use Octopy\Debug\DebugBar;

class InjectDebugBar
{
    /**
     * @var array
     */
    protected $except = [
        '__debugbar'
    ];

    /**
     * @var Octopy\Debug\DebugBar
     */
    protected $debugbar;

    /**
     * @param Application $app
     * @param DebugBar    $debugbar
     */
    public function __construct(Application $app, DebugBar $debugbar)
    {
        $this->debugbar = $debugbar;
        $this->except = array_merge($this->except, $app['config']['debugbar.except']);
    }

    /**
     * @param  Request $request
     * @param  Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->debugbar->enabled() || $request->is($this->except)) {
            return $next($request);
        }
       
        try {
            $response = $next($request);
        } catch (Throwable $exception) {
            throw $exception;
        }

        return $this->debugbar->modify($response);
    }
}
