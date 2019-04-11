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

class ViewClearCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'view:clear';

    /**
     * @var string
     */
    protected $description = 'Clear all compiled view files';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        $iterator = $this->app['filesystem']->iterator(
            $this->app['config']['view.compiled']
        );

        foreach ($iterator as $row) {
            try {
                unlink($row);
            } catch (Throwable $exception) {
                continue;
            }
        }

        return $output->success('Compiled views cleared.');
    }
}
