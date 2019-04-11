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

namespace App\Provider;

use Octopy\Provider\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $namespace = \App\HTTP\Controller::class;

    /**
     * @return void
     */
    public function register()
    {
        // http
        $route = $this->app['route'];
        $route->namespace($this->namespace, function () use ($route) {
            $route->load('Web.php');

            $route->prefix('api', function () use ($route) {
                $route->load('Api.php');
            });
        });

        // cli
        if ($this->app->console()) {
            $this->app['console']->load('Console.php');
        }
    }
}
