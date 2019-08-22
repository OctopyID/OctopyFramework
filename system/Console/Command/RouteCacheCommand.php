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

namespace Octopy\Console\Command;

use Closure;
use Exception;
use LogicException;
use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;

class RouteCacheCommand extends Command
{
    /**
     * @var string
     */
    protected $command = 'route:cache';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $argument = [];

    /**
     * @var string
     */
    protected $description = 'Create a route cache file for faster route registration';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        $this->prepare(
            $collection = $this->app->router->collection
        );

        try {
            $cache = $this->app['path']->writeable('route.php');

            // we encrypting the content to confused attacker,
            // because sometimes there's contains a sensitive contents
            $encrypted = $this->app['encrypter']->encrypt($collection);

            if ($this->generate($cache, 'Cache', ['SerializedContent' => $encrypted])) {
                return $output->success('Route cached successfully.');
            }
        } catch (Exception $exception) {
            return $output->error('Failed generating autoload cache.');
        }
    }

    /**
     * @param  Collection $collection
     * @return void
     */
    private function prepare($collection) : void
    {
        foreach ($collection as $routes) {
            foreach ($routes as $route) {
                if ($route->controller instanceof Closure) {
                    throw new LogicException(
                        "Unable to prepare route ['$route->uri'] for serialization. Uses Closure."
                    );
                }
            }
        }
    }
}
