<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

namespace Octopy\Provider;

use Octopy\Encryption\Exception\DecryptException;

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
        $cache = $this->app->writeable('route.php');

        if (file_exists($cache)) {
            try {
                $this->app['router']->load(
                    $this->app['encrypter']->decrypt(require $cache)
                );
            } catch (DecryptException $exception) {
                if (! $this->app->console()) {
                    throw new DecryptException('The MAC is invalid, please re-run route cache command.');
                }
            }
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
