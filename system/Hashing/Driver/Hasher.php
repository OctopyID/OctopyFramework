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

namespace Octopy\Hashing\Driver;

abstract class Hasher
{
    /**
     * @param  string $hashed
     * @return array
     */
    public function info($hashed) : array
    {
        return password_get_info($hashed);
    }

    /**
     * @param  string $value
     * @param  string $hashed
     * @param  array  $option
     * @return bool
     */
    public function verify($value, $hashed, array $option = []) : bool
    {
        if (strlen($hashed) === 0) {
            return false;
        }

        return password_verify($value, $hashed);
    }
}
