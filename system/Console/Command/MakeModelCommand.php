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

class MakeModelCommand extends Command
{
    /**
     * @var string
     */
    protected $command = 'make:model';

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
    protected $description = 'Create a new database model class';

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

        if (file_exists($location = $this->app['path']->app->DB($parsed['location']))) {
            return $output->warning('Model already exists.');
        }

        if (($table = $argv->get('-t')) === false && ($table = $argv->get('--table')) === false) {
            $table = mb_strtolower($parsed['classname']);
        }

        $data = [
            'DummyTableName' => $table,
            'DummyNameSpace' => $parsed['namespace'],
            'DummyClassName' => $parsed['classname'],
        ];

        if ($this->generate($location, 'Model', $data)) {
            return $output->success('Model created successfully.');
        }
    }
}
