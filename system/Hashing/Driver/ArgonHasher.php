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

class ArgonHasher extends Hasher
{
    /**
     * @var int
     */
    protected $time = 2;

    /**
     * @var int
     */
    protected $thread = 2;

    /**
     * @var int
     */
    protected $memory = 1024;

    /**
     * @var bool
     */
    protected $verify = false;

    /**
     * @param array $option
     */
    public function __construct(array $option = [])
    {
        $this->time = $option['time'] ?? $this->time;
        $this->memory = $option['memory'] ?? $this->memory;
        $this->thread = $option['thread'] ?? $this->thread;
        $this->verify = $option['verify'] ?? $this->verify;
    }

    /**
     * @param  string  $value
     * @param  array  $option
     * @return string
     */
    public function make($value, array $option = []) : string
    {
        $hash = password_hash($value, $this->algorithm(), [
            'threads'     => $this->thread($option),
            'time_cost'   => $this->time($option),
            'memory_cost' => $this->memory($option),
        ]);

        if ($hash === false) {
            throw new RuntimeException('Argon2 hashing not supported.');
        }

        return $hash;
    }

    /**
     * @return int
     */
    protected function algorithm()
    {
        return PASSWORD_ARGON2I;
    }

    /**
     * @param  string $value
     * @param  string $hashed
     * @param  array  $option
     * @return bool
     */
    public function verify($value, $hashed, array $option = []) : bool
    {
        if ($this->verify && $this->info($hashed)['algoName'] !== 'argon2i') {
            throw new RuntimeException('This password does not use the Argon2i algorithm.');
        }

        return parent::verify($value, $hashed, $option);
    }

    /**
     * @param  string $hashed
     * @param  array  $option
     * @return bool
     */
    public function rehash($hashed, array $option = [])
    {
        return password_needs_rehash($hashed, $this->algorithm(), [
            'threads'     => $this->thread($option),
            'time_cost'   => $this->time($option),
            'memory_cost' => $this->memory($option),
        ]);
    }
    
    /**
     * @param  array $option
     * @return int
     */
    protected function memory(array $option) : int
    {
        return $option['memory'] ?? $this->memory;
    }

    /**
     * @param  array $option
     * @return int
     */
    protected function time(array $option) : int
    {
        return $option['time'] ?? $this->time;
    }

    /**
     * @param  array $option
     * @return int
     */
    protected function thread(array $option) : int
    {
        return $option['thread'] ?? $this->thread;
    }
}
