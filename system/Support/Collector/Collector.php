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

namespace Octopy\Support;

class Collector
{
    /**
     * @var array
     */
    protected static $collection;

    /**
     * @param  string $name
     * @param  mixed  $data
     * @return void
     */
    public static function collect(string $name, $data) : void
    {
        static::$collection[$name] = $data;
    }

    /**
     * @param  string $name
     * @return bool
     */
    public static function has(string $name) : bool
    {
        return isset(static::$collection[$name]);
    }

    /**
     * @param  string $name
     * @param  mixed  $default
     * @return mixed
     */
    public static function get(string $name, $default = null)
    {
        return static::$collection[$name] ?? $default;
    }
}
