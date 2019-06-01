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

use Exception;
use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;

class MakeMigrationCommand extends Command
{
    /**
     * @var string
     */
    protected $command = 'make:migration';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $argument = [
        'name' => 'The name of the class',
    ];

    /**
     * @var string
     */
    protected $description = 'Create a new migration class';

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

        if (file_exists($location = $this->app['path']->app->DB->migration($parsed['location']))) {
            return $output->warning('Migration already exists.');
        }

        if (($table = $argv->get('-t')) === false && ($table = $argv->get('--table')) === false) {
            $table = strtolower($parsed['classname']);
        }

        $data = [
            'DummyTimeStamp' => time(),
            'DummyTableName' => $table,
            'DummyNameSpace' => $parsed['namespace'],
            'DummyClassName' => $parsed['classname'],
        ];

        if ($this->generate($location, 'Migration', $data)) {
            return $output->success('Migration created successfully.');
        }
    }
}
