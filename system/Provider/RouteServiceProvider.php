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

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @return void
     */
    public function boot()
    {
        if (file_exists($cache = $this->app->storage('framework/route.php'))) {
            $this->app['router']->load(unserialize(base64_decode(
                require $cache
            )));
        } else {
            if (method_exists($this, 'map')) {
                $this->map();
            }

            $this->app->boot(function () {
                $this->app['router']->collection->refresh();
            });
        }
    }
}
