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
use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;

class AutoloadCacheCommand extends Command
{
    /**
     * @var string
     */
    protected $command = 'autoload:cache';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $argument = [];

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
        $classmap = $this->classmap();

        try {
            $cache = $this->app['path']->writeable('autoload.php');

            // we encrypting the content to confused attacker,
            // because sometimes there's contains a sensitive contents
            $encrypted = $this->app['encrypter']->encrypt($classmap);

            if ($this->generate($cache, 'Cache', ['SerializedContent' => $encrypted])) {
                return $output->success('Autoload cached successfully.');
            }
        } catch (Exception $exception) {
            return $output->error('Failed generating autoload cache.');
        }
    }

    /**
     * @return array
     */
    private function classmap() : array
    {
        $basepath = $this->app->basepath();

        // pattern for skipping vendor files
        $composer = '/' . str_replace('/', '\/', preg_quote($basepath) . 'vendor') . '/';

        $classmap = [];
        foreach ($this->app['filesystem']->iterator($basepath) as $splfile) {

            // we do not discover all files form "vendor"
            // cause it's already handled by Composer
            if (! $splfile->isFile() || preg_match($composer, $splfile)) {
                continue;
            }

            if (mb_substr($splfile->getFilename(), -4) === '.php') {
                $classname = preg_replace(['/^system/', '/^app/'], ['Octopy', 'App'], implode('\\', array_unique(explode('/', str_replace($basepath, '', mb_substr($splfile = $splfile->getRealpath(), 0, -4))))));

                if (! preg_match('/^Octopy|^App/', $classname)) {
                    continue;
                }

                $classmap[$classname] = $splfile;
            }
        }

        return $classmap;
    }
}
