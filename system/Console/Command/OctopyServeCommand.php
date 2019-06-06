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
    protected $command = 'serve';

    /**
     * @var array
     */
    protected $option = [
        '--port[=PORT]' => 'The port to serve the application on [default: 1337]',
    ];

    /**
     * @var array
     */
    protected $argument = [];

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

        $info = $output->format(
            '<c:green>Octopy CLI Tool <c:white>- Version ' . $this->app->version() . ' - Server Time : ' . date('Y-m-d H:i:s')
        );
        $info .= "\n";
        $info .= $output->format('<c:yellow>Octopy development server started : <c:white>http://localhost:' . $port, true);
        $info .= $output->format('<c:white>Press Control-C to stop development server.');

        echo $info;

        foreach (['system', 'shell', 'shell_exec', 'exec'] as $shell) {
            if (function_exists($shell)) {
                $shell('php -S localhost:' . $port . ' -t ' . $this->app['path']->public());
                break;
            }
        }
    }
}
