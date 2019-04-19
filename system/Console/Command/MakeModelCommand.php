<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 *
 * @author  : Supian M <supianidz@gmail.com>
 *
 * @link    : www.octopy.xyz
 *
 * @license : MIT
 */

namespace Octopy\Console\Command;

use Octopy\Console\Argv;
use Octopy\Console\Command;
use Octopy\Console\Output;

class MakeModelCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'make:model';

    /**
     * @var string
     */
    protected $description = 'Create a new model class';

    /**
     * @param Argv   $argv
     * @param Output $output
     *
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        $parsed = $this->parse($argv);
        if (file_exists($location = $this->app['path']->app->DB($parsed['location']))) {
            return $output->warning('Model already exists.');
        }

        if (($table = $argv->get('-t')) === false && ($table = $argv->get('--table')) === false) {
            $table = strtolower($parsed['classname']);
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
