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

use RuntimeException;
use Octopy\Encryption\Encrypter;

class EncryptionServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        if ($this->app->console()) {
            return;
        }

        $key = $this->key(
            $config = $this->app['config']['app']
        );

        $this->app->instance('encrypter', new Encrypter($key, $config['cipher']));
    }

    /**
     * @param  array $config
     * @return string
     */
    protected function key(array $config) : string
    {
        if (empty($key = $config['key'])) {
            throw new RuntimeException(
                'No application encryption key has been specified.'
            );
        }

        if (preg_match('/^base64:/', $key)) {
            $key = base64_decode(substr($key, 7));
        }

        return $key;
    }
}
