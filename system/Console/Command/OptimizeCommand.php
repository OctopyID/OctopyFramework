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

class OptimizeCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'optimize';

    /**
     * @var string
     */
    protected $description = 'Cache the framework for better performance';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        echo $this->call('autoload:cache');

        echo $this->call('view:clear');
        echo $this->call('view:cache');

        try {
            echo $this->call('route:cache');
        } catch (Exception $exception) {
        }
    }
}
