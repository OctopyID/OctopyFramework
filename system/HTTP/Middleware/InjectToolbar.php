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

namespace Octopy\HTTP\Middleware;

use Closure;
use Throwable;
use Octopy\Application;
use Octopy\HTTP\Request;
use Octopy\Debug\Toolbar;

class InjectToolbar
{
    /**
     * @var array
     */
    protected $except = [];

    /**
     * @var Octopy\Debug\Toolbar
     */
    protected $toolbar;

    /**
     * @param Application $app
     * @param Toolbar    $toolbar
     */
    public function __construct(Application $app, Toolbar $toolbar)
    {
        $this->app      = $app;
        $this->toolbar = $toolbar;
    }

    /**
     * @param  Request $request
     * @param  Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        // excepting
        $except = array_merge($this->app['config']['toolbar.except'], (array) ('/'. $this->app['config']['toolbar.prefix'] . '*'));

        if (! $this->toolbar->enabled() || $request->is($except)) {
            return $next($request);
        }

        try {
            $response = $next($request);
        } catch (Throwable $exception) {
            throw $exception;
        }

        $this->toolbar->boot($this->app)
                       ->write($this->app);

        if ($this->app->config['toolbar.inject']) {
            $response = $this->toolbar->modify($response);
        }

        return $response;
    }
}
