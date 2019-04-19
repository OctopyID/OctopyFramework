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

use Octopy\FileSystem;

class Storage extends FileSystem
{
    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $compiled;

    /**
     * @param string $template
     * @param string $compiled
     */
    public function __construct(string $template, ?string $compiled)
    {
        $this->template = $template;
        $this->compiled = $compiled;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->octopy() && $this->compiled) {
            return $this->compiled;
        }

        if ($this->octopy() && ! $this->compiled) {
            return $this->content;
        }

        return $this->source();
    }

    /**
     * @return string
     */
    public function template() : string
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function compiled() : ?string
    {
        return $this->compiled;
    }

    /**
     * @return bool
     */
    public function octopy() : bool
    {
        return substr($this->template, -11) === '.octopy.php' || substr($this->template, -7) === '.octopy';
    }

    /**
     * @param string $content
     */
    public function write(string $content)
    {
        $this->content = $content;

        if (! is_null($this->compiled)) {
            if ($this->mkdir(dirname($this->compiled))) {
                $this->put($this->compiled, $content);
            }
        }
    }

    /**
     * @return string
     */
    public function source()
    {
        return $this->get($this->template);
    }

    /**
     * @return bool
     */
    public function expired() : bool
    {
        if (is_null($this->compiled) || ! file_exists($this->compiled)) {
            return true;
        }

        return filemtime($this->template) > filemtime($this->compiled);
    }

    /**
     * @param  [type] $time
     * @param  [type] $memory
     * @return [type]
     */
    public function benchmark($time, $memory)
    {
        $this->info = compact('time', 'memory');
    }

    /**
     * @param  string $name
     * @return mixed
     */
    public function info(string $name = null)
    {
        return $this->info[$name] ?? $this->info;
    }
}
