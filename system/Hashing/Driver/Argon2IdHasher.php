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

use RuntimeException;

class Argon2IdHasher extends ArgonHasher
{
    /**
     * @param  string  $value
     * @param  string  $hashed
     * @param  array  $option
     * @return bool
     */
    public function verify($value, $hashed, array $option = []) : bool
    {
        if ($this->verify && $this->info($hashed)['algoName'] !== 'argon2id') {
            throw new RuntimeException('This password does not use the Argon2id algorithm.');
        }

        if (strlen($hashed) === 0) {
            return false;
        }

        return password_verify($value, $hashed);
    }

    /**
     * @return int
     */
    protected function algorithm()
    {
        return PASSWORD_ARGON2ID;
    }
}
