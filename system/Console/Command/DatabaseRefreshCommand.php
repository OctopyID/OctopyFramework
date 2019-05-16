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

class DatabaseRefreshCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'database:refresh';

    /**
     * @var string
     */
    protected $description = 'Drop all tables and re-run all migration and seeder';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        echo $this->call('db:migrate', [
            '--seed'    => true,
            '--refresh' => true,
        ]);
    }
}
