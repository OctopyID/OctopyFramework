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

class MaintenanceUpCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'up';

    /**
     * @var string
     */
    protected $description = 'Bring the application out of maintenance mode';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        $down = $this->app['path']->storage('framework') . 'down';
        if (file_exists($down)) {
            unlink($down);
        }

        return $output->success('Application is now live.');
    }
}
