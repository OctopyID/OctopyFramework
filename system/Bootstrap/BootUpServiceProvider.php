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

namespace Octopy\Bootstrap;

use Throwable;
use Octopy\Application;

class BootUpServiceProvider
{
    /**
     * @param Application $app
     */
    public function bootstrap(Application $app)
    {
        try {
            $app->booting();
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
