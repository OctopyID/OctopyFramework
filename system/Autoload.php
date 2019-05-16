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
    private $aliases = [];

    /**
     * @var array
     */
    private $classmap = [];

    /**
     * @var array
     */
    private $namespace = [];

    /**
     * @param string $basepath
     * @param array  $namespace
     */
    public function __construct(string $basepath, array $namespace = [])
    {
        $this->basepath = $basepath;
        $this->namespace = $namespace;

        spl_autoload_register([$this, 'load'], true, true);
    }

    /**
     * @param  string $namespace
     * @param  string $directory
     * @return bool
     */
    public function namespace(string $namespace, string $directory) : bool
    {
        $this->namespace = array_merge($this->namespace, [
            $namespace => $directory,
        ]);

        return true;
    }

    /**
     * @param  string $namespace
     * @return bool
     */
    public function remove(string $namespace) : bool
    {
        if (array_key_exists($namespace, $this->namespace)) {
            unset($this->namespace[$namespace]);
        }

        return true;
    }

    /**
     * @param  array $classmap
     * @return void
     */
    public function classmap(array $classmap) : void
    {
        $this->classmap = array_merge($this->classmap, $classmap);
    }

    /**
     * @param  array $aliases
     * @return void
     */
    public function aliases(array $aliases) : void
    {
        $this->aliases = array_merge($this->aliases, $aliases);
    }

    /**
     * @param  string $class
     * @return string
     */
    public function load(string $class)
    {
        // Lazy load for class aliases
        // so they don't hinder performance
        if (isset($this->aliases[$class])) {
            return class_alias($this->aliases[$class], $class);
        }

        if (isset($this->classmap[$class]) && file_exists($this->classmap[$class])) {
            return require $this->classmap[$class];
        }

        $class = trim(str_replace(BS, DS, $class), DS);
        foreach ($this->namespace as $namespace => $directory) {
            if (preg_match($pattern = '/^' . $namespace . '/', $class)) {
                $classpath = str_replace(BS, DS, preg_replace($pattern, $directory, $class));
                if ($fullpath = $this->require($classpath)) {
                    return $fullpath;
                }

                $pieces[] = array_slice($pieces = explode(DS, $classpath), -1)[0];

                if ($fullpath = $this->require(implode(DS, $pieces))) {
                    return $fullpath;
                }
            }
        }

        return false;
    }

    /**
     * @param  string $filepath
     * @return string
     */
    public function require(string $filepath)
    {
        if (is_file($filepath = $this->basepath . $filepath . '.php')) {
            if (require_once $filepath) {
                return $filepath;
            }
        }
    }
}
