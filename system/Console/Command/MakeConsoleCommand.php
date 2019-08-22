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

use Exception;
use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;

class MakeConsoleCommand extends Command
{
    /**
     * @var string
     */
    protected $command = 'make:command';

    /**
     * @var array
     */
    protected $options = [
        '--command[=COMMAND]' => 'The terminal command that should be assigned [default: "command:name"]',
    ];

    /**
     * @var array
     */
    protected $argument = [
        'name' => 'The name of the class',
    ];

    /**
     * @var string
     */
    protected $description = 'Create a new console command';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        try {
            $parsed = $this->parse($argv);
        } catch (Exception $exception) {
            return $output->error('Not enough arguments (missing : "name").');
        }

        if (file_exists($location = $this->app['path']->app->console->command($parsed['location']))) {
            return $output->warning('Command already exists.');
        }


        if (! ($command = $argv->get('--command'))) {
            $command = 'command:name';
        }

        $data = [
            'DummyClassName' => $parsed['classname'],
            'DummyCommandName' => $command,
        ];

        if ($this->generate($location, 'Command', $data)) {
            return $output->success('Command created successfully.');
        }
    }
}
