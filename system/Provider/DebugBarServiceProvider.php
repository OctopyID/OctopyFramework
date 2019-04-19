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

class DebugBarServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $namespace = \Octopy\Debug\DebugBar::class;

    /**
     * @return void
     */
    public function register()
    {
        if ($this->app->debug() === true) {
            $option = [
                'prefix'    => '__debugbar',
                'namespace' => $this->namespace,
            ];

            $this->app['route']->group($option, function ($route) {
                $route->get('/', 'DebugBarController@index')->name('debugbar');
                $route->get(':filename', 'DebugBarController@assets')->name('debugbar.assets');
            });
        }
    }
}
