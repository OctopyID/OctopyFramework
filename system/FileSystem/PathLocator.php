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

namespace Octopy\FileSystem;

use Octopy\Application;

class PathLocator
{
    /**
     * @var string
     */
    protected $basepath;

    /**
     * @var array
     */
    protected $subpath = [];

    /**
     * @var array
     */
    protected $extension = ['octopy', 'php', 'html', 'js', 'css', 'cache', 'png', 'jpg', 'ico', 'svg', 'mp4', 'flv', 'mp3', 'log'];

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->basepath = $app->basepath();
    }

    /**
     * @param  string $path
     * @return string
     */
    public function __invoke(string $path)
    {
        return $this->basepath . $path;
    }

    /**
     * @param  string $path
     * @return $this
     */
    public function __get(string $path) : PathLocator
    {
        if (! empty($this->subpath)) {
            if ($path !== 'app' && ! in_array($path, ['app', 'system', 'public', 'storage'])) {
                $path = ucfirst($path);
            }
        }

        $this->subpath[] = $path;

        return $this;
    }

    /**
     * @param  string $method
     * @param  array  $parameter
     * @return string
     */
    public function __call(string $path, array $additional = [])
    {
        if (! in_array($path, ['app', 'system', 'public', 'storage'])) {
            $path = ucfirst($path);
        }

        $this->subpath[] = $path;

        if (isset($additional[0])) {
            $this->subpath[] = $additional[0];
            if (! preg_match('/\.(' . implode('|', $this->extension) . ')/', strtolower($additional[0]))) {
                $this->subpath[] = '/';
            }
        } else {
            $this->subpath[] = '/';
        }

        $location = $this->basepath . implode('/', $this->subpath);

        $this->subpath = [];

        return preg_replace('/\/+/', '/', $location);
    }
}
