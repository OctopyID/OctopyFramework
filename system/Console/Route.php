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
     * @param array  $argument
     * @param string $handler
     * @param string $describe
     */
    public function __construct(string $command, array $option, array $argument, $handler, string $describe)
    {
        $this->data = compact('command', 'describe', 'option', 'argument', 'handler');
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
     * @return Route
     */
    public function handler($handler) : Route
    {
        return $this->update('handler', $handler);
    }

    /**
     * @param  array $option
     * @return Route
     */
    public function option(array $option) : Route
    {
        return $this->update('option', array_merge($this->data['option'], $option));
    }

    /**
     * @param  array  $argument
     * @return Route
     */
    public function argument(array $argument) : Route
    {
        return $this->update('argument', array_merge($this->data['argument'], $option));
    }

    /**
     * @param  string $describe
     * @return Route
     */
    public function describe(?string $describe) : Route
    {
        return $this->update('describe', trim($describe));
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return Route
     */
    protected function update(string $key, $value) : Route
    {
        $this->data[$key] = $value;

        return $this;
    }
}
