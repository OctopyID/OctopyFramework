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

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;

class ViewCacheCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'view:cache';

    /**
     * @var string
     */
    protected $description = 'Compile all of the application\'s Octopy templates';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        $iterator = $this->app['filesystem']->iterator($this->app['config']['view.resource']);
        
        foreach ($iterator as $row) {
            $filename = str_replace(['.octopy.php', '.php'], '', $row->getFilename());
            $this->app['view']->render($filename, [], false);
        }

        return $output->success('Templates cached successfully.');
    }
}
