<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : www.octopy.xyz
 * @license : MIT
 */

namespace Octopy\HTTP;

use Octopy\Application;

class Kernel
{
    /**
     * @var array
     */
    protected $bootstrap = [
        \Octopy\Bootstrap\RegisterEnvironmentVariable::class,
        \Octopy\Bootstrap\RegisterSystemConfiguration::class,
        \Octopy\Bootstrap\RegisterMiddlewareProvider::class,
        \Octopy\Bootstrap\RegisterExceptionHandler::class,
        \Octopy\Bootstrap\RegisterServiceProvider::class,
        \Octopy\Bootstrap\BootUpServiceProvider::class,
    ];

    /**
     * @param Application $app
     * @param Middleware  $middleware
     */
    public function __construct(Application $app, Middleware $middleware)
    {
        $this->app = $app;

        // These middleware are run during every request to your application.
        if (isset($this->middleware)) {
            foreach ($this->middleware as $layer) {
                $middleware->set($layer);
            }
        }

        // These middleware may be assigned to groups or used individually.
        if (isset($this->routemiddleware)) {
            foreach ($this->routemiddleware as $name => $layer) {
                $middleware->set($name, $layer);
            }
        }

        // Trying to starting up our bootstapper
        try {
            foreach ($this->bootstrap as $bootstrap) {
                $app->make($bootstrap)->bootstrap($app);
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param  Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        try {
            return $this->app['router']->dispatch($request);
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * @param Request  $request
     * @param Response $response
     */
    public function terminate(Request $request, Response $response)
    {
        //
    }
}
