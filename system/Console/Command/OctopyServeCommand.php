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

class OctopyServeCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'serve';

    /**
     * @var string
     */
    protected $description = 'Serve the application on the PHP development server';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        if (($port = $argv->get('-p')) === false && ($port = $argv->get('--port')) === false) {
            $port = 1337;
        }

        echo $output->format('{yellow}Octopy development server started : {white}http://localhost:' . $port, true);

        foreach (['system', 'shell', 'shell_exec', 'exec'] as $shell) {
            if (function_exists($shell)) {
                $shell('cd ' . $this->app['path']->public() . ' && php -S localhost:' . $port);
                break;
            }
        }
    }
}
