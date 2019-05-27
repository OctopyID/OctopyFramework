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

use Octopy\Encryption\Exception\DecryptException;

class AutoloadServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $autoload = $this->app['path']->writeable('Autoload.php');

        if (file_exists($autoload)) {
            try {
                $this->app['autoload']->classmap(
                    $this->app['encrypter']->decrypt(require $autoload)
               );
            } catch (DecryptException $exception) {
                if (! $this->app->console()) {
                    throw new DecryptException('The MAC is invalid, please re-run autoload cache command.');
                }
            }
        }
    }
}
