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
        $cache = $this->app->writeable();
        $cache .= '9C46408A3BC655C68505C57A11D6C4EE';

        if (file_exists($cache)) {
            $this->app['router']->load(
                $this->app['encrypter']->decrypt(file_get_contents($cache))
            );
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
