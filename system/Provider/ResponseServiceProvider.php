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

namespace Octopy\Provider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $app = $this->app;
        $app->response->macro('flash', function (string $name, array $flash) use ($app) {
            $app['session']->set($name, $flash);

            return $this;
        });

        $app->macro('flash', function (string $name) use ($app) {
            return $app['session']->pull($name, []);
        });
    }
}
