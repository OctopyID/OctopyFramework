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
        $this->basepath = $this->app->basepath();

        $classmap = [];
        foreach ($this->app['filesystem']->iterator($this->basepath) as $row) {
            if (!$row->isFile()) {
                continue;
            }
            
            if (substr($row->getFilename(), -4) === '.php') {
                $classname = preg_replace(['/^system/', '/^app/'], ['Octopy', 'App'], implode('\\', array_unique(explode('/', str_replace($this->basepath, '', substr($row = $row->getRealpath(), 0, -4))))));

                if (!preg_match('/^Octopy|^App/', $classname)) {
                    continue;
                }

                $classmap[$classname] = $row;
            }
        }

        try {
            $banner[] = "/**                                          ";
            $banner[] = " *   ___       _                             ";
            $banner[] = " *  / _ \  ___| |_ ___  _ __  _   _          ";
            $banner[] = " * | | | |/ __| __/ _ \| '_ \| | | |         ";
            $banner[] = " * | |_| | (__| || (_) | |_) | |_| |         ";
            $banner[] = " *  \___/ \___|\__\___/| .__/ \__, |         ";
            $banner[] = " *                     |_|    |___/          ";
            $banner[] = " * @author  : Supian M <supianidz@gmail.com> ";
            $banner[] = " * @link    : www.octopy.xyz                 ";
            $banner[] = " * @license : MIT                            ";
            $banner[] = " */                                          ";
            
            $template = sprintf("<?php \n\n%s\n\nreturn %s;", implode("\n", $banner), var_export($classmap, true));

            if (!is_dir($location = dirname($classmap = $this->app['path']->storage('framework/autoload.php')))) {
                $this->app->mkdir($location, 0755, true);
            }

            $message = 'Generating autoload cache.';
            if (file_exists($classmap)) {
                $message = 'Re-Generating autoload cache.';
            }

            if ($this->app['filesystem']->put($classmap, $template)) {
                return $output->success($message);
            }
        } catch (Exception $exception) {
            return $output->error('Failed generating autoload cache.');
        }
    }
}
