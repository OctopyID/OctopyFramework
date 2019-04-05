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

namespace Octopy\Bootstrap;

use Octopy\Application;

class RegisterMiddlewareProvider
{
    /**
     * @param Application $app
     */
    public function bootstrap(Application $app)
    {
        $app['middleware']->set(\Octopy\HTTP\Middleware\ValidatePostSize::class);

        if ($app['config']['debugbar.enable']) {
            $app['middleware']->set(\Octopy\HTTP\Middleware\DebugBar::class);
        }
    }
}
