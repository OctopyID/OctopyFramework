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

use Octopy\Console\Argv;
use Octopy\Console\Route;
use Octopy\Console\Output;
use Octopy\Console\Collection;
use Octopy\Console\Dispatcher;

class Console
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app, Collection $collection)
    {
        $this->app = $app;
        $this->collection = $collection;
    }

    /**
     * @param string $command
     * @param mixed  $handler
     * @param array  $option
     */
    public function command(string $command, $handler = null, array $option = [])
    {
        return $this->collection->set(
            new Route($command, $option, $handler)
        );
    }

    /**
     * @return array
     */
    public function all() : array
    {
        return $this->collection->all();
    }

    /**
     * @param  Argv   $input
     * @param  Output $output
     * @return string
     */
    public function dispatch(Argv $input, Output $output)
    {
        $command = $input->command();

        if (is_null($command)) {
            return $output->help();
        }

        if ($this->has($command)) {
            return $this->call($command);
        }

        return $output->undefined($command);
    }

    /**
     * @param  string $command
     * @return bool
     */
    public function has(?string $command) : bool
    {
        return isset($this->collection[$command]);
    }

    /**
     * @param  string $command
     * @return string
     */
    public function call(?string $command)
    {
        return (new Dispatcher($this->app, $this->collection[$command]))->run();
    }

    /**
     * @param string $route
     */
    public function load(string $route)
    {
        require $this->app['path']->app->route($route);
    }
}
