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
            if (! is_dir($location = $this->app['path']->writeable())) {
                $this->app->mkdir($location, 0755, true);
            }

            // we hashing the route name & encrypted content
            // to confused attacker, because sometimes there's
            // contains a sensitive contents
            $location .= '9C46408A3BC655C68505C57A11D6C4EE';
            $encrypted = chunk_split($this->app['encrypter']->encrypt($collection));

            if ($this->app['filesystem']->put($location, $encrypted)) {
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
