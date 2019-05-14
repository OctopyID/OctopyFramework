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

use Octopy\Support\Facade\App;

if (! function_exists('app')) {
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

if (! function_exists('bcrypt')) {
    /**
     * @param  string $value
     * @param  array  $option
     * @return string
     */
    function bcrypt($value, $option = null)
    {
        return App::make('hash')->driver('bcrypt')->make($value, $option);
    }
}

if (! function_exists('config')) {
    /**
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return App::make('config');
        }

        if (is_array($key)) {
            return App::make('config')->set($key);
        }

        return App::make('config')->get($key, $default);
    }
}

if (! function_exists('csrf')) {
    /**
     * @return string
     */
    function csrf() : string
    {
        return App::make(Octopy\HTTP\Middleware\VerifyCSRFToken::class)->generate();
    }
}

if (! function_exists('dd')) {
    /**
     * @param  mixed $dump
     * @return void
     */
    function dd(...$dump)
    {
        App::make('vardumper')->dd(...$dump);
    }
}

if (! function_exists('decrypt')) {
    /**
     * @param  string $value
     * @return mixed
     */
    function decrypt($value)
    {
        return App::make('encrypter')->decrypt($value);
    }
}

if (! function_exists('dump')) {
    /**
     * @param  mixed $dump
     * @return void
     */
    function dump(...$dump)
    {
        App::make('vardumper')->dump(...$dump);
    }
}

if (! function_exists('encrypt')) {
    /**
     * @param  string $value
     * @return mixed
     */
    function encrypt($value)
    {
        return App::make('encrypter')->encrypt($value);
    }
}

if (! function_exists('env')) {
    /**
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function env(string $key, $default = null)
    {
        return App::make('env')->get($key, $default);
    }
}

if (! function_exists('head')) {
    /**
     * @param  array $array
     * @return mixed
     */
    function head(array $array)
    {
        return reset($array);
    }
}

if (! function_exists('last')) {
    /**
     * @param  array $array
     * @return mixed
     */
    function last(array $array)
    {
        return end($array);
    }
}

if (! function_exists('byteformatter')) {
    /**
     * @param  float $byte
     * @return string
     */
    function byteformatter($byte)
    {
        if ($byte < 1024) {
            return ' ' . $byte . 'B';
        } elseif ($byte < 1048576) {
            return ' ' . round($byte / 1024, 2) . 'KB';
        }

        return ' ' . round($byte / 1048576, 2) . 'MB';
    }
}

if (! function_exists('route')) {
    /**
     * @param  string $name
     * @param  array  $default
     * @return string
     */
    function route(string $name, $default = []) : string
    {
        if (! is_array($default)) {
            $default = (array) $default;
        }

        return App::make(Octopy\HTTP\Routing\URLGenerator::class)->route($name, $default);
    }
}

if (! function_exists('url')) {
    /**
     * @param  string $path
     * @return string
     */
    function url(string $url) : string
    {
        return App::make('url')->url($url);
    }
}

if (! function_exists('value')) {
    /**
     * @param  mixed $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}
