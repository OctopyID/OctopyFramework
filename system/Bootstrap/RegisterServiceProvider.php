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

namespace Octopy\Bootstrap;

use Octopy\Application;

class RegisterServiceProvider
{
    /**
     * @param  Application $app
     */
    public function bootstrap(Application $app)
    {
        $array = $app->config->get('app', []);

        // register class aliases
        $app['autoload']->aliases($array['aliases'] ?? []);

        // register service provider
        usort($array['provider'], static function ($provider) {
            return mb_substr($provider, 0, 3) === 'App';
        });

        $array['provider'] = array_merge(['Octopy\Provider\EncryptionServiceProvider'], $array['provider']);

        foreach ($array['provider'] as $provider) {
            $app->register($provider, true);
        }
    }
}
