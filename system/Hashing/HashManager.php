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

namespace Octopy\Hashing;

use Octopy\Application;
use Octopy\Hashing\Driver\ArgonHasher;
use Octopy\Hashing\Driver\BcryptHasher;
use Octopy\Hashing\Driver\Argon2IdHasher;
use Octopy\Hashing\Exception\HashDriverException;

class HashManager
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var string
     */
    protected $driver;

    /**
     * @var Octopy\Hashing\Driver\Hasher
     */
    protected $hasher;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param  string $driver
     * @return void
     */
    public function driver(string $driver = null)
    {
        $driver = strtolower($driver ?? $this->app['config']['hashing.driver']);

        if ($this->hasher && $this->driver === $driver) {
            return $this->hasher;
        }

        switch ($this->driver = $driver) {
            case 'argon':
                return $this->hasher = new ArgonHasher(
                    $this->app['config']['hashing.argon']
                );
            case 'argon2id':
                return $this->hasher = new Argon2IdHasher(
                    $this->app['config']['hashing.argon']
                );
            case 'bcrypt':
                return $this->hasher = new BcryptHasher(
                    $this->app['config']['hashing.bcrypt']
                );
        }

        throw new HashDriverException("Undefined Hash driver : $driver.");
    }

    /**
     * @param  string  $hashed
     * @return array
     */
    public function info($hashed)
    {
        return $this->driver()->info($hashed);
    }

    /**
     * @param  string $value
     * @param  array  $option
     * @return string
     */
    public function make($value, array $option = [])
    {
        return $this->driver()->make($value, $option);
    }

    /**
     * @param  string $value
     * @param  string $hashed
     * @param  array  $option
     * @return bool
     */
    public function verify($value, string $hashed, array $option = []) : bool
    {
        return $this->driver()->verify($value, $hashed, $option);
    }

    /**
     * @param  string $hashed
     * @param  array  $option
     * @return bool
     */
    public function rehash(string $hashed, array $option = [])
    {
        return $this->driver()->rehash($hashed, $option);
    }
}
