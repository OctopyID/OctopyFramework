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

class AutoloadClearCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'autoload:clear';

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
        if (file_exists($autoload = $this->app['path']->storage('framework/autoload.php'))) {
            unlink($autoload);
        }

        return $output->success('Autoload cache cleared.');
    }
}
