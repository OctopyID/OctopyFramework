<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

namespace Octopy\Database;

class Queries
{
    /**
     * @var array
     */
    protected static $queries = [];

    /**
     * @param  string $query
     */
    public static function collect(string $query)
    {
        static::$queries[] = $query;
    }

    /**
     * @return array
     */
    public static function all() : array
    {
        return static::$queries;
    }
}
