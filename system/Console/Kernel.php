<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : www.octopy.xyz
 * @license : MIT
 */

namespace Octopy\Console;

use Octopy\Application;

class Kernel
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $bootstrap = [
        \Octopy\Bootstrap\RegisterEnvironmentVariable::class,
        \Octopy\Bootstrap\RegisterSystemConfiguration::class,
        \Octopy\Bootstrap\RegisterExceptionHandler::class,
        \Octopy\Bootstrap\RegisterServiceProvider::class,
        \Octopy\Bootstrap\BootUpServiceProvider::class,
    ];

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        try {
            foreach ($this->bootstrap as $bootstrap) {
                $app->make($bootstrap)->bootstrap($app);
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        try {
            return $this->app['console']->dispatch($argv, $output);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param Argv $argv
     */
    public function terminate(Argv $argv)
    {
        //
    }
}
