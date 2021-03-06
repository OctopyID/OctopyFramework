<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

namespace Octopy\Bootstrap;

use Octopy\FileSystem;
use Octopy\Application;
use Octopy\Config\Repository;

class RegisterSystemConfiguration
{
    /**
     * @var Octopy\FileSystem\PathLocator
     */
    protected $path;

    /**
     * @var Octopy\FileSystem
     */
    protected $filesystem;

    /**
     * @param  FileSystem $filesystem
     */
    public function __construct(FileSystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param  Application $app
     */
    public function bootstrap(Application $app)
    {
        // set path locator
        $this->path = $app->path;

        // set item to config repository
        $app->instance('config', $config = new Repository(
            $this->search($this->path->app->config())
        ));

        // setting up default config
        mb_internal_encoding('UTF-8');
        date_default_timezone_set($config->get('app.timezone', 'UTC'));
    }

    /**
     * @param  string $path
     * @return array
     */
    protected function search(string $path) : array
    {
        $config = [];

        $iterator = $this->filesystem->iterator($path);
        foreach ($iterator as $row) {
            if ($row->isDir()) {
                continue;
            }

            $key = mb_strtolower(mb_substr($row->getFilename(), 0, -4));

            $config[$key] = require $row->getRealpath();
        }

        if (array_key_exists('constant', $config)) {
            unset($config['constant']);
        }

        return $config;
    }
}
