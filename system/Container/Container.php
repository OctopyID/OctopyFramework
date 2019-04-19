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

namespace Octopy;

use Closure;
use Throwable;
use ArrayAccess;
use ReflectionClass;
use ReflectionException;
use Octopy\Container\Exception\BindingResolutionException;

class Container implements ArrayAccess
{
    /**
     * @var array
     */
    protected static $aliases;

    /**
     * @var array
     */
    protected static $instances = [];

    /**
     * @var array
     */
    protected static $concretes = [];

    /**
     * @var array
     */
    protected static $parameters = [];

    /**
     * @param  string $abstract
     * @return object
     */
    public function __get(string $abstract)
    {
        return static::make($abstract);
    }

    /**
     * @param string   $abstract
     * @param callable $instance
     */
    public function __set(string $abstract, $instance)
    {
        static::instance($abstract, $instance);
    }

    /**
     * @param  string $abstract
     * @return bool
     */
    public static function has(string $abstract) : bool
    {
        return isset(static::$instances[$abstract]);
    }

    /**
     * @param  string $abstract
     * @param  mixed  $instance
     * @return mixed
     */
    public static function instance(string $abstract, $instance)
    {
        $abstract = static::alias($abstract);
        if (! array_key_exists($abstract, static::$instances)) {
            static::$instances[$abstract] = $instance;
        }

        return $instance;
    }

    /**
     * @param string $abstract
     */
    public static function unset(string $abstract)
    {
        $abstract = static::alias($abstract);
        if (array_key_exists($abstract, static::$instances)) {
            unset(static::$instances[$abstract]);
        }

        return new Container;
    }

    /**
     * @param  string $abstract
     * @param  mixed  $concrete
     * @return string
     */
    public static function alias(string $abstract, $concrete = null)
    {
        if (is_null($concrete)) {
            return static::$aliases[$abstract] ?? $abstract;
        }

        static::$aliases[$abstract] = $concrete;
    }

    /**
     * @param  string $abstract
     * @param  array  $parameter
     * @return object
     */
    public static function make(string $abstract, array $parameter = [])
    {
        return static::resolve($abstract, $parameter);
    }

    /**
     * @param  mixed $abstract
     * @param  array $parameter
     * @return object
     */
    public static function resolve($abstract, array $parameter = [])
    {
        $abstract = static::alias($abstract);

        if (! empty($parameter)) {
            static::unset($abstract);
        }

        if (isset(static::$instances[$abstract])) {
            return static::$instances[$abstract];
        }

        static::$parameters[] = $parameter;

        try {
            $object = static::build($abstract);
        } catch (Throwable $exception) {
            throw $exception;
        }

        array_pop(static::$parameters);

        return static::instance($abstract, $object);
    }

    /**
     * @param  mixed $concrete
     * @return object
     */
    protected static function build($concrete)
    {
        $parameter = count(static::$parameters) ? end(static::$parameters) : [];

        if ($concrete instanceof Closure) {
            return $concrete($this, $parameter);
        }

        $reflector = new ReflectionClass($concrete);

        if (! $reflector->isInstantiable()) {
            if (empty(static::$concretes)) {
                throw new BindingResolutionException(
                    sprintf('Class [%s] is not instantiable.', $concrete)
                );
            } else {
                throw new BindingResolutionException(
                    sprintf('Class [%s] is not instantiable while building [%s].', $concrete, implode(', ', static::$concretes))
                );
            }
        }

        static::$concretes[] = $concrete;

        if (is_null($constructor = $reflector->getConstructor())) {
            array_pop(static::$concretes);

            return $reflector->newInstance();
        }

        $depedencies = [];
        foreach ($constructor->getParameters() as $dependency) {
            if (array_key_exists($dependency->name, $parameter)) {
                $depedencies[] = $parameter[$dependency->name];
            } else {
                try {
                    if (! is_null($dependency->getClass())) {
                        try {
                            $depedencies[] = static::make($dependency->getClass()->name);
                        } catch (BindingResolutionException $exception) {
                            if ($dependency->isOptional()) {
                                $depedencies[] = $dependency->getDefaultValue();
                            }

                            throw $exception;
                        }
                    } else {
                        if (! $dependency->isDefaultValueAvailable()) {
                            throw new BindingResolutionException(
                                sprintf('Unresolvable dependency resolving [%s] in class [%s]', $dependency, $dependency->getDeclaringClass()->getName())
                            );
                        }

                        $depedencies[] = $dependency->getDefaultValue();
                    }
                } catch (ReflectionException $exception) {
                    throw new BindingResolutionException(
                        sprintf('Unresolvable dependency resolving [%s] in class [%s]', $dependency, $dependency->getDeclaringClass()->getName())
                    );
                }
            }
        }

        array_pop(static::$concretes);

        return $reflector->newInstanceArgs($depedencies);
    }

    /**
     * @param  string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return static::has($key);
    }

    /**
     * @param  string $key
     * @return object
     */
    public function offsetGet($key)
    {
        return static::make($key);
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return mixed
     */
    public function offsetSet($key, $value)
    {
        return static::instance($key, $value);
    }

    /**
     * @param string $key
     */
    public function offsetUnset($key)
    {
        static::unset($key);
    }
}
