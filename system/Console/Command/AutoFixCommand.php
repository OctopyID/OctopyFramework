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
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;

class AutoFixCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'fix';

    /**
     * @var string
     */
    protected $description = 'Fix permission storage';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        if (!is_dir($storage = $this->app->basepath('storage'))) {
            mkdir($storage, 0755, true);
        }

        chown($storage, 'www-data');

        return $output->success('Storage permission was successfully fixed');
    }
}
