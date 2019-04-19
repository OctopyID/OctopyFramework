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

namespace Octopy\Console;

use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

class Collection implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @var array
     */
    protected $command = [];

    /**
     * @param Route $command
     */
    public function set(Route $command)
    {
        $this->command = array_merge($this->command, [
            $command->command() => $command,
        ]);

        return $command;
    }

    /**
     * @return array
     */
    public function all() : array
    {
        asort($this->command);

        return $this->command;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->command);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->command);
    }

    /**
     * @param  string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return isset($this->command[$key]);
    }

    /**
     * @param  string $key
     * @return object
     */
    public function offsetGet($key)
    {
        return $this->command[$key] ?? null;
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return mixed
     */
    public function offsetSet($key, $value)
    {
        //
    }

    /**
     * @param string $key
     */
    public function offsetUnset($key)
    {
        unset($this->command[$key]);
    }
}
