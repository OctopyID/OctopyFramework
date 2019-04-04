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

namespace Octopy\Provider;

class DebugBarServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $namespace = \Octopy\DebugBar::class;
    
    /**
     * @return void
     */
    public function register()
    {
        if ($this->app->debug() === true) {
            $option = array(
                'prefix'    => '__debugbar',
                'namespace' => $this->namespace
            );

            $this->app->route->group($option, function ($route) {
                $route->get('assets/:dir/:file', 'DebugBarController@assets')->name('debugbar.assets');
            });
        }
    }
}
