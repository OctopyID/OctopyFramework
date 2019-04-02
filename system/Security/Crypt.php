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

namespace Octopy\Security;

use Octopy\Application;
use Octopy\Security\Exception\EncryptionKeyException;

class Crypt
{
    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->config = $app->config['security'];
        if (!$this->config['key']) {
            throw new EncryptionKeyException;
        }
    }

    /**
     * @param  string $string
     * @return string
     */
    public function encrypt(string $string) : string
    {
        return base64_encode(openssl_encrypt($string, $this->config->cipher, $this->config->key, OPENSSL_RAW_DATA, str_repeat(chr(0x0), 16)));
    }

    /**
     * @param  string $encrypted
     * @return string
     */
    public function decrypt(string $encrypted) : string
    {
        return openssl_decrypt(base64_decode($encrypted), $this->config['cipher'], $this->config['key'], OPENSSL_RAW_DATA, str_repeat(chr(0x0), 16));
    }
}
