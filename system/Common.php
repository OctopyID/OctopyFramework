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

use Octopy\Support\App;

if (!function_exists('app')) {
    /**
     * @param  string  $abstract
     * @param  array   $parameter
     * @return mixed
     */
    function app(string $abstract = null, array $parameter = [])
    {
        if (is_null($abstract)) {
            return App::instance();
        }

        return App::instance()->make($abstract, $parameter);
    }
}

if (!function_exists('csrf')) {
    /**
     * @return string
     */
    function csrf() : string
    {
        return App::make(Octopy\HTTP\Middleware\CSRFVerifyToken::class)->generate();
    }
}

if (!function_exists('dd')) {
    /**
     * @param  mixed $dump
     * @return void
     */
    function dd(...$dump)
    {
        App::make(Octopy\Support\VarDumper::class)->dd(...$dump);
    }
}

if (!function_exists('dump')) {
    /**
     * @param  mixed $dump
     * @return void
     */
    function dump(...$dump)
    {
        App::make(Octopy\Support\VarDumper::class)->dump(...$dump);
    }
}

if (!function_exists('env')) {
    /**
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function env(string $key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? false;
        }

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
                return true;
            case 'false':
                return false;
            case 'empty':
                return '';
            case 'null':
                return null;
        }

        return $value;
    }
}

if (!function_exists('head')) {
    /**
     * @param  array $array
     * @return mixed
     */
    function head(array $array)
    {
        return reset($array);
    }
}

if (!function_exists('last')) {
    /**
     * @param  array $array
     * @return mixed
     */
    function last(array $array)
    {
        return end($array);
    }
}

if (!function_exists('route')) {
    /**
     * @param  string $name
     * @param  array  $default
     * @return string
     */
    function route(string $name, array $default = []) : string
    {
        return App::make(Octopy\HTTP\Routing\URLGenerator::class)->route($name, $default);
    }
}

if (!function_exists('value')) {
    /**
     * @param  mixed $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}
