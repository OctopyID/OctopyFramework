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

class AutoloadCacheCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'autoload:cache';

    /**
     * @var string
     */
    protected $description = 'Create a cache for faster class loading';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        $basepath = $this->app->basepath();

        // pattern for skipping vendor files
        $composer = '/' . str_replace('/', '\/', preg_quote($basepath) . 'vendor') . '/';

        $classmap = [];
        foreach ($this->app['filesystem']->iterator($basepath) as $splfile) {

            // we do not discover all files form "vendor"
            // cause it's already handled by Composer
            if (!$splfile->isFile() || preg_match($composer, $splfile)) {
                continue;
            }

            if (substr($splfile->getFilename(), -4) === '.php') {
                $classname = preg_replace(['/^system/', '/^app/'], ['Octopy', 'App'], implode('\\', array_unique(explode('/', str_replace($basepath, '', substr($splfile = $splfile->getRealpath(), 0, -4))))));

                if (!preg_match('/^Octopy|^App/', $classname)) {
                    continue;
                }

                $classmap[$classname] = $splfile;
            }
        }

        try {
            if (!is_dir($location = $this->app['path']->writeable())) {
                $this->app->mkdir($location, 0755, true);
            }

            // we hashing the autoload name & encrypted content
            // to confused attacker, because sometimes there's
            // contains a sensitive contents
            $location .= '46AE3E009A9883E4F2C38542E300A16D';
            $encrypted = chunk_split($this->app['encrypter']->encrypt($classmap));

            if ($this->app['filesystem']->put($location, $encrypted)) {
                return $output->success('Autoload cached successfully.');
            }
        } catch (Exception $exception) {
            return $output->error('Failed generating autoload cache.');
        }
    }
}
