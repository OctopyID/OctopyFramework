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

namespace Octopy\Provider;

use Octopy\Application;
use Octopy\Console\Output;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        if ($this->app->console()) {
            $console = $this->app->console;

            $console->command('--help', function (Output $output) {
                return $output->help();
            })->describe('Display this help message');

            $console->command('--version', function (Application $app, Output $output) {
                return 'Octopy Framework ' . $output->success($app->version());
            })->describe('Display this application version');

            // auto
            $this->autodiscover();
        }
    }

    /**
     * @return void
     */
    private function autodiscover()
    {
        $autodiscover = array(
            'App\\Console\\Command\\' => $this->app->path->app->console->command(),
            'Octopy\\Console\\Command\\' => $this->app->path->system->console->command()
        );

        foreach ($autodiscover as $namespace => $directory) {
            foreach ($this->app->filesystem->iterator($directory) as $row) {
                if (substr($row->getFilename(), -4) !== '.php') {
                    continue;
                }

                if (($class = substr($row->getFilename(), 0, -4)) === 'Command') {
                    continue;
                }

                $console = $this->app->make($class = $namespace . $class);

                $this->app->console->command($console->signature, $class)->describe($console->description);
            }
        }
    }
}
