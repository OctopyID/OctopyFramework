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

class AutoloadServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        if (file_exists($autoload = $this->app['path']->storage('framework/autoload.php'))) {
            $this->app['autoload']->classmap(require $autoload);
        }
    }
}
