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
        $autoload  = $this->app['path']->writeable();

        // we hashing the autoload name & encrypted content
        // to confused attacker, because sometimes there's
        // contains a sensitive contents
        $autoload .= '46AE3E009A9883E4F2C38542E300A16D';

        if (file_exists($autoload)) {
            $this->app['autoload']->classmap(
                $this->app['encrypter']->decrypt(file_get_contents($autoload))
            );
        }

        if ($composer = $this->app['config']['app.composer']) {
            if (is_string($composer) && $composer != null) {
                $composer = substr($composer, 0, -4);
            } else {
                $composer = 'vendor/autoload';
            }

            $this->app['autoload']->require($composer);
        }
    }
}
