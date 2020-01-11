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

use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;
use Octopy\Encryption\Encrypter;

class GenerateAppKeyCommand extends Command
{
    /**
     * @var string
     */
    protected $command = 'key:generate';

    /**
     * @var array
     */
    protected $options = [
        '--show' => 'Display the key instead of modifying files',
    ];

    /**
     * @var array
     */
    protected $argument = [];

    /**
     * @var string
     */
    protected $description = 'Set the application key';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        $key = $this->key();

        if ($argv->get('--show')) {
            return $output->warning($key);
        }

        // Next, we will replace the application key in the environment file so it is
        // automatically setup for this developer. This key gets generated using a
        // secure random byte generator and is later base64 encoded for storage.
        $this->write($key);

        $this->app['config']['app.key'] = $key;

        return $output->success('Application key set successfully.');
    }

    /**
     * @return string
     */
    protected function key() : string
    {
        return 'base64:' . base64_encode(
            Encrypter::generate($this->app['config']['app.cipher'])
        );
    }

    /**
     * @param  string  $key
     * @return void
     */
    protected function write($key)
    {
        $lines = file($environment = $this->app->basepath() . '.env');
        foreach ($lines as $i => $line) {
            if (preg_match('/^APP_KEY/', $line = trim($line))) {
                $array    = explode('=', $line, 2);
                $array[1] = $key;
                $line     = implode('= ', $array);
            }

            $lines[$i] = $line;
        }

        file_put_contents($environment, implode("\n", $lines));
    }
}
