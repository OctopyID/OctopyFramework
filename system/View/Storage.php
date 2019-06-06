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

final class Storage extends FileSystem
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $compiled;

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
     * @param  string $property
     * @return string
     */
    public function __get(string $property) : ?string
    {
        return $this->$property ?? null;
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
        return mb_substr($this->template, -11) === '.octopy.php' || mb_substr($this->template, -7) === '.octopy';
    }

    /**
     * @param  string $content
     * @return void
     */
    public function write(string $content) : void
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
    public function source() : string
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
}
