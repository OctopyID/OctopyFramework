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

class RouteClearCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'route:clear';

    /**
     * @var string
     */
    protected $description = 'Remove the route cache file';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        $cache  = $this->app->writeable();
        $cache .= '9C46408A3BC655C68505C57A11D6C4EE';

        if (file_exists($cache)) {
            unlink($cache);
        }

        return $output->success('Route cache cleared.');
    }
}
