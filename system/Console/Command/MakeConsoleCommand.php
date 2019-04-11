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

use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;

class MakeConsoleCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'make:command';

    /**
     * @var string
     */
    protected $description = 'Create a new Octopy command';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        $parsed = $this->parse($argv);
        if (file_exists($location = $this->app['path']->app->console->command($parsed['location']))) {
            return $output->warning('Command already exists.');
        }

        $data = array(
            'DummyClassName' => $parsed['classname'],
        );
        
        if ($this->generate($location, 'Command', $data)) {
            return $output->success('Command created successfully.');
        }
    }
}
