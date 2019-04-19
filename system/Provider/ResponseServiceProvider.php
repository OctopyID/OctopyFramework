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
    public function register(): void
    {
        $app = $this->app;
        $app->response->macro('flash', function (array $flash) use ($app) {
            $app->session->set('error', $flash);

            return $this;
        });

        $app->macro('flash', function () use ($app) {
            return $app->session->pull('error', []);
        });
    }
}
