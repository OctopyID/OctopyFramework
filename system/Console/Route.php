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

namespace Octopy\Console;

class Route
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param string $command
     * @param array  $option
     * @param mixed  $handler
     * @param string $describe
     */
    public function __construct(string $command, array $option = [], $handler, string $describe = '')
    {
        $this->data = compact('command', 'describe', 'option', 'handler');
    }

    /**
     * @param  string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @return string
     */
    public function command() : string
    {
        return $this->data['command'];
    }

    /**
     * @param  mixed $handler
     * @return $this
     */
    public function handler($handler)
    {
        return $this->update('handler', $handler);
    }

    /**
     * @param  array $option
     * @return $this
     */
    public function option(array $option)
    {
        return $this->update('option', array_merge($this->data['option'], $option));
    }

    /**
     * @param  string $describe
     * @return $this
     */
    public function describe(?string $describe)
    {
        return $this->update('describe', trim($describe));
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return $this
     */
    protected function update(string $key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }
}
