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

class BcryptHasher extends Hasher
{
    /**
     * @var int
     */
    protected $round = 10;

    /**
     * @var bool
     */
    protected $verify = false;

    /**
     * @param  array $option
     * @return void
     */
    public function __construct(array $option = [])
    {
        $this->round = $option['round'] ?? $this->round;
    }

    /**
     * @param  string $value
     * @param  array  $option
     * @return string
     */
    public function make($value, array $option = [])
    {
        $hash = password_hash($value, PASSWORD_BCRYPT, [
            'cost' => $this->cost($option),
        ]);

        if ($hash === false) {
            throw new RuntimeException('Bcrypt hashing not supported.');
        }

        return $hash;
    }

    /**
     * @param  string $value
     * @param  string $hashed
     * @param  array  $option
     * @return bool
     */
    public function verify($value, $hashed, array $option = []) : bool
    {
        if ($this->verify && $this->info($hashed)['algoName'] !== 'bcrypt') {
            throw new RuntimeException('This password does not use the Bcrypt algorithm.');
        }

        return parent::verify($value, $hashed, $option);
    }

    /**
     * @param  string $hashed
     * @param  array   $option
     * @return bool
     */
    public function rehash($hashed, array $option = [])
    {
        return password_needs_rehash($hashed, PASSWORD_BCRYPT, [
            'cost' => $this->cost($option),
        ]);
    }

    /**
     * @param  array $option
     * @return int
     */
    protected function cost(array $option = [])
    {
        return $option['round'] ?? $this->round;
    }
}
