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

class Benchmark
{
    /**
     * @var array
     */
    protected static $marker = [];

    /**
     * @param  string $name
     * @param  float  $time
     */
    public static function mark(string $name, float $time = null)
    {
        static::$marker[$name] = $time ?? microtime(true);
    }

    /**
     * @param  string $start
     * @param  string $end
     * @param  int    $decimal
     * @return string
     */
    public static function elapsed(string $start, string $end = null, int $decimal = 4) : string
    {
        if ($start === '') {
            return '{elapsed}';
        }

        if (!isset(static::$marker[$start])) {
            return '';
        }

        if (!isset(static::$marker[$end])) {
            static::$marker[$end] = microtime(true);
        }

        return number_format(static::$marker[$end] - static::$marker[$start], $decimal);
    }

    /**
     * @return string
     */
    public static function memory()
    {
        return memory_get_usage(true);
    }
}
