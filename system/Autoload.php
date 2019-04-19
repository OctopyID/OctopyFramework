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

namespace Octopy;

final class Autoload
{
    /**
     * @var string
     */
    private $basepath;

    /**
     * @var array
     */
    private $autoload = [];

    /**
     * @var array
     */
    private $classmap = [];

    /**
     * @param string $basepath
     * @param array  $autoload
     */
    public function __construct(string $basepath, array $autoload = [])
    {
        $this->basepath = $basepath;
        $this->autoload = $autoload;

        spl_autoload_register([$this, 'load'], true, true);
    }

    /**
     * @param string $namespace
     * @param string $directory
     */
    public function set(string $namespace, string $directory)
    {
        $this->autoload = array_merge($this->autoload, [
            $namespace => $directory,
        ]);
    }

    /**
     * @param array $classmap
     */
    public function classmap(array $classmap)
    {
        $this->classmap = array_merge($this->classmap, $classmap);
    }

    /**
     * @return void
     */
    public function composer()
    {
        $this->require('vendor/autoload');
    }

    /**
     * @param  string $class
     * @return string
     */
    protected function load(string $class)
    {
        if (isset($this->classmap[$class]) && file_exists($this->classmap[$class])) {
            return require $this->classmap[$class];
        }

        $class = str_replace(BS, DS, $class);
        foreach ($this->autoload as $namespace => $directory) {
            if (preg_match($pattern = '/^' . $namespace . '/', $class)) {
                $classpath = str_replace(BS, DS, preg_replace($pattern, $directory, $class));
                if ($this->require($classpath)) {
                    return true;
                }

                $pieces[] = array_slice($pieces = explode(DS, $classpath), -1)[0];

                if ($this->require(implode(DS, $pieces))) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param  string $filepath
     * @return string
     */
    protected function require(string $filepath)
    {
        if (file_exists($filepath = $this->basepath . $filepath . '.php')) {
            return require $filepath;
        }
    }
}
