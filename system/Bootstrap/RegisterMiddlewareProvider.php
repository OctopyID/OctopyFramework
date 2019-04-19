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

namespace Octopy\Bootstrap;

use Octopy\Application;
use Octopy\HTTP\Middleware;

class RegisterMiddlewareProvider
{
    /**
     * @var Octopy\HTTP\Middleware
     */
    protected $middleware;

    /**
     * @param Middleware $middleware
     */
    public function __construct(Middleware $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * @param Application $app
     */
    public function bootstrap(Application $app)
    {
        $this->middleware->set(\Octopy\HTTP\Middleware\ValidatePostSize::class);

        if ($app['config']['app.debug'] && $app['config']['debugbar.enable']) {
            // $this->middleware->set(\Octopy\HTTP\Middleware\InjectDebugBar::class);
        }
    }
}
