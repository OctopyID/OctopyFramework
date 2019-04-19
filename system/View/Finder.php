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

namespace Octopy\View;

use Octopy\View\Exception\ViewException;

class Finder
{
    /**
     * @var array
     */
    protected $resource;

    /**
     * @var string
     */
    protected $compiled;

    /**
     * @var array
     */
    protected $template = [];

    /**
     * @param array  $resource
     * @param string $compiled
     */
    public function __construct($resource = [], ?string $compiled = null)
    {
        if (! is_array($resource)) {
            $resource = (array) $resource;
        }

        $this->resource = $resource;
        $this->compiled = $compiled;
    }

    /**
     * @param mixed $resource
     */
    public function set($resource)
    {
        $this->resource = array_merge($this->resource, (array) $resource);
    }

    /**
     * @return array
     */
    public function template() : array
    {
        return $this->template;
    }

    /**
     * @param  string $name
     * @return Storage
     */
    public function find(string $name) : Storage
    {
        if (array_key_exists($name, $this->template)) {
            return $this->template[$name];
        }

        $compiled = $this->compiled ? $this->compiled . md5($name) . '.php' : null;

        $template = str_replace('.', '/', $name);
        foreach ($this->resource as $resource) {
            $resource = array_map(function ($extension) use ($resource, $template) {
                return $resource . $template . $extension;
            }, ['.octopy.php', '.octopy', '.php']);

            foreach ($resource as $pathname) {
                if (file_exists($pathname)) {
                    return $this->template[$name] = new Storage($pathname, $compiled);
                }
            }
        }

        throw new ViewException(
            sprintf('Unable to find template [%s] looked in [%s].', $name, implode(', ', $this->resource))
        );
    }
}
