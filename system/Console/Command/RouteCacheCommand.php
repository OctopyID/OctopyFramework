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

namespace Octopy\Console\Command;

use Closure;
use Exception;
use LogicException;

use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;
use Octopy\HTTP\Routing\Collection;

class RouteCacheCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'route:cache';

    /**
     * @var string
     */
    protected $description = 'Create a route cache file for faster route registration';

    /**
     * @var Octopy\HTTP\Routing\Collection
     */
    protected $collection;

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

        $location = $this->app->storage('framework/route.php');

        $this->generate($location, 'Route', [
            'SerializedContent' => base64_encode(serialize(
                $collection
            ))
        ]);

        return $output->success('Routes cached successfully.');
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
