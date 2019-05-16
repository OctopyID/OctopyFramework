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

namespace Octopy\Support;

use Octopy\Container;

abstract class Facade
{
    /**
     * @var Octopy\Container
     */
    protected static $container;

    /**
     * @var array
     */
    protected static $resolved = [];

    /**
     * @param  string $method
     * @param  array  $parameter
     * @return mixed
     */
    public static function __callStatic(string $method, array $parameter = [])
    {
        return static::instance()->$method(...$parameter);
    }

    /**
     * @return mixed
     */
    public static function instance()
    {
        if (array_key_exists(static::$name, static::$resolved)) {
            return static::$resolved[static::$name];
        }

        return static::$resolved[static::$name] = Container::make(static::$name);
    }
}
