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

use Closure;
use ReflectionMethod;
use Octopy\Application;
use Octopy\Support\Arr;
use ReflectionFunction;

class Dispatcher
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var Octopy\Console\Route
     */
    protected $command;

    /**
     * @param Application $app
     * @param Route     $command
     */
    public function __construct(Application $app, Route $command)
    {
        $this->app = $app;
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function run() : ?string
    {
        $parameter = [];
        if (! $this->command->handler instanceof Closure) {
            $parameter = $this->class($parameter, $command = $this->app->make($this->command->handler), 'handle');

            return $this->trim($command->handle(...array_values($parameter)));
        }

        $parameter = $this->method($parameter, new ReflectionFunction($this->command->handler));

        return $this->trim(call_user_func($this->command->handler, ...array_values($parameter)));
    }

    /**
     * @param  array  $parameter
     * @param  object $instance
     * @param  string $method
     * @return array
     */
    protected function class(array $parameter, $instance, string $method)
    {
        if (! method_exists($instance, $method)) {
            return $parameter;
        }

        return $this->method($parameter, new ReflectionMethod($instance, $method));
    }

    /**
     * @param  array   $parameter
     * @param  unknown $reflector
     * @return array
     */
    public function method(array $parameter, $reflector) : array
    {
        $count = 0;

        $array = array_values($parameter);

        foreach ($reflector->getParameters() as $key => $dependency) {
            $instance = $this->transform($dependency, $parameter);

            if (! is_null($instance)) {
                $count++;
                $this->splice($parameter, $key, $instance);
            } elseif (! isset($array[$key - $count]) && $dependency->isDefaultValueAvailable()) {
                $this->splice($parameter, $key, $dependency->getDefaultValue());
            }
        }

        return $parameter;
    }

    /**
     * @param  unknown $dependency
     * @param  array   $parameter
     * @return mixed
     */
    protected function transform($dependency, array $parameter)
    {
        $class = $dependency->getClass();

        if ($class && ! $this->already($class->name, $parameter)) {
            return $dependency->isDefaultValueAvailable() ? $dependency->getDefaultValue() : $this->app->make($class->name);
        }
    }

    /**
     * @param  string $class
     * @param  array  $parameter
     * @return bool
     */
    protected function already($class, array $parameter)
    {
        return ! is_null(Arr::first($parameter, function ($array) use ($class) {
            return $array instanceof $class;
        }));
    }

    /**
     * @param  array  $parameter
     * @param  string $offset
     * @param  mixed  $array
     * @return void
     */
    protected function splice(array &$parameter, $offset, $array)
    {
        array_splice($parameter, $offset, 0, [$array]);
    }

    /**
     * @param  string $output
     * @return string
     */
    protected function trim($output)
    {
        if (is_string($output)) {
            return rtrim($output, "\n") . "\n";
        }

        return $output;
    }
}
