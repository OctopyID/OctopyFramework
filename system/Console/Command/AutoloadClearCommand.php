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

use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;

class AutoloadClearCommand extends Command
{
    /**
     * @var string
     */
    protected $command = 'autoload:clear';

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
    protected $description = 'Clear autoload cache';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        if (file_exists($autoload = $this->app['path']->storage('autoload.php'))) {
            unlink($autoload);
        }

        return $output->success('Autoload cache cleared.');
    }
}
