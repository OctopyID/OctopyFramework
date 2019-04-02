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

use Closure;
use ReflectionClass;
use ReflectionMethod;
use BadMethodCallException;

trait Macroable
{
    /**
     * @var array
     */
    protected static $macro = [];

    /**
     * @param  string    $name
     * @param  callable  $macro
     * @return void
     */
    public static function macro(string $name, $macro)
    {
        static::$macro[$name] = $macro;
    }

    /**
     * @param  object  $mixin
     * @return void
     */
    public static function mixin($mixin)
    {
        $methods = (new ReflectionClass($mixin))->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
        );

        foreach ($methods as $method) {
            $method->setAccessible(true);

            static::macro($method->name, $method->invoke($mixin));
        }
    }

    /**
     * @param  string  $method
     * @param  array   $parameter
     * @return mixed
     */
    public static function __callStatic($method, $parameter)
    {
        if (!isset(static::$macro[$method])) {
            throw new BadMethodCallException(
                sprintf('Method %s::%s does not exist.', static::class, $method)
            );
        }

        if (static::$macro[$method] instanceof Closure) {
            return call_user_func_array(Closure::bind(static::$macro[$method], null, static::class), $parameter);
        }

        return call_user_func_array(static::$macro[$method], $parameter);
    }

    /**
     * @param  string  $method
     * @param  array   $parameter
     * @return mixed
     */
    public function __call($method, $parameter)
    {
        if (!isset(static::$macro[$method])) {
            throw new BadMethodCallException(
                sprintf('Method %s::%s does not exist.', static::class, $method)
            );
        }

        $macro = static::$macro[$method];

        if ($macro instanceof Closure) {
            return call_user_func_array($macro->bindTo($this, static::class), $parameter);
        }

        return call_user_func_array($macro, $parameter);
    }
}
