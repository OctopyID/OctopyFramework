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

namespace Octopy\HTTP\Routing;

class Compiler
{
    /**
     * @var int
     */
    protected static $close = 0;

    /**
     * @param  string $target
     * @return array
     */
    public static function parse(string $target) : array
    {
        return [
            static::regexp($target),
            static::option($target),
        ];
    }

    /**
     * @param  string $target
     * @return array
     */
    protected static function option(string $target) : array
    {
        $target = str_replace('?', '', $target);
        if (preg_match_all('/(?<=\/):([^\/]+)(?=\/|$)/', $target, $match)) {
            return array_filter(array_fill_keys($match[1], null), static function ($key) {
                return is_string($key);
            }, ARRAY_FILTER_USE_KEY);
        }

        return [];
    }

    /**
     * @param  string $target
     * @return string
     */
    protected static function regexp(string $target) : string
    {
        if (preg_match_all('/(?<=\/):([^\/]+)(?=\/|$)/', $target, $match)) {
            $offsetx = 0;
            $pattern = '/';
            foreach (preg_split('/\//', $target, null, PREG_SPLIT_NO_EMPTY) as $value) {
                if (substr($value, 0, 1) === ':') {
                    $pattern .= sprintf(static::compute($match[1], $offsetx), trim($match[1][$offsetx], '?'));
                    $offsetx++;
                } else {
                    $pattern .= '/' . $value;

                    if (! isset($match[1][$offsetx]) || substr($match[1][$offsetx], -1) !== '?') {
                        $pattern .= '/';
                    }

                    if ($offsetx > 1) {
                        $offsetx--;
                    }
                }
            }

            $pattern = preg_replace('/\/+/', '/', rtrim($pattern .= str_repeat(')?', static::$close), '/'));
        }

        static::$close = 0;

        return '#^' . ($pattern ?? str_replace('/', '\/', $target)) . '$#sDu';
    }

    /**
     * @param  array $array
     * @param  int   $offset
     * @return string
     */
    protected static function compute(array $array, int $offset = 0) : string
    {
        $pattern = '(?P<%s>[^/]++)';

        if ($offset > 0 && substr($array[$offset], -1) === '?') {
            $pattern = '(?:/' . $pattern;

            ++static::$close;
        }

        if ($offset === 0 && substr($array[$offset], -1) === '?') {
            $pattern .= '?';
        }

        if (static::next($array, $offset) && static::next($array, $offset + 1)) {
            $pattern .= '/';
        }

        return $pattern;
    }

    /**
     * @param  array $array
     * @param  int   $offset
     * @return bool
     */
    protected static function next(array $array, int $offset) : bool
    {
        if (! isset($array[$offset])) {
            return true;
        }

        return strstr($array[$offset], '?') !== '?';
    }
}
