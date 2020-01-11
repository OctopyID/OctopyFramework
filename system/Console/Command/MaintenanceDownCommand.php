<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

namespace Octopy\Console\Command;

use Exception;
use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;

class MaintenanceDownCommand extends Command
{
    /**
     * @var string
     */
    protected $command = 'maintenance:down';

    /**
     * @var array
     */
    protected $options = [
        '--message[=MESSAGE]' => 'The message for the maintenance mode',
        '--allow[=ALLOW]'     => 'IP or networks allowed to access the application while in maintenance mode',
    ];

    /**
     * @var array
     */
    protected $argument = [];

    /**
     * @var string
     */
    protected $description = 'Put the application into maintenance mode';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        $message = 'Sorry, we are doing some maintenance. Please check back soon.';
        if ($argv->get('--message')) {
            $message = $argv->get('--message');
        }

        $allowed = [];
        if ($argv->get('--allow')) {
            $allowed = array_map('trim', explode(',', $argv->get('--allow')));
        }

        try {
            $location = $this->app['path']->storage('maintenance.json');

            $this->app['filesystem']->put($location, json_encode([
                'time'    => time(),
                'message' => $message,
                'allowed' => $allowed,
            ], JSON_PRETTY_PRINT));

            return $output->warning('Application is now in maintenance mode.');
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
