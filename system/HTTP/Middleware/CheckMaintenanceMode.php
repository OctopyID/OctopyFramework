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

use Octopy\Application;
use Octopy\HTTP\Request;
use Octopy\HTTP\Middleware\Exception\MaintenanceModeException;

class CheckMaintenanceMode
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $except = [];

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param  Request $request
     * @param  Closure $next
     * @return Request
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is($this->except)) {
            return $next($request);
        }

        if (file_exists($down = $this->app['path']->storage('framework') . 'down')) {
            $down = json_decode($this->app['filesystem']->get($down));
            
            if (in_array($request->ip(), $down->allowed)) {
                return $next($request);
            }

            throw new MaintenanceModeException($down->message);
        }

        return $next($request);
    }
}
