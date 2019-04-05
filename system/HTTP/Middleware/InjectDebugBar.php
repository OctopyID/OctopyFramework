<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @version : v1.0
 * @license : MIT
 */

namespace Octopy\HTTP\Middleware;

use Closure;

use Octopy\DebugBar;
use Octopy\Application;
use Octopy\HTTP\Request;

class InjectDebugBar
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var Octopy\DebugBar
     */
    protected $debugbar;

    /**
     * @param Application $app
     * @param DebugBar    $debugbar
     */
    public function __construct(Application $app, DebugBar $debugbar)
    {
        $this->app = $app;
        $this->debugbar = $debugbar;
    }

    /**
     * @param  Request $request
     * @param  Closure $next
     * @return Request
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->app->debug() || !$this->app->config['debugbar.enable']) {
            return $next($request);
        }

        $excluding = array_merge($this->app['config']['debugbar.except'], [
            '__debugbar'
        ]);

        $uri = $request->path();
        foreach ($excluding as $except) {
            if (preg_match('/' . $except . '/', $uri)) {
                return $next($request);
            }
        }
        
        try {
            $response = $next($request);
        } catch (Exception $e) {
        }

        $this->debugbar->modify($response);

        return $response;
    }
}
