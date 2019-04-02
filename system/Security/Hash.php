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

class Hash
{
    /**
     * @var int
     */
    const BCRYPT = PASSWORD_BCRYPT;

    /**
     * @var int
     */
    const DEFAULT = PASSWORD_DEFAULT;

    /**
     *
     */
    protected $config;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->config = $app->config['security.bcrypt'];
    }


    /**
     * @param  string $string
     * @param  int    $algoritma
     * @return string
     */
    public function make(string $string, int $algoritma = null) : string
    {
        $algoritma = $algoritma ?? Hash::DEFAULT;
        return password_hash($string, $algoritma, [
            'cost' => $this->config['cost']
        ]);
    }

    /**
     * @param  string $string
     * @param  string $hash
     * @return bool
     */
    public function verify(string $string, string $hash) : bool
    {
        return password_verify($string, $hash);
    }
}
