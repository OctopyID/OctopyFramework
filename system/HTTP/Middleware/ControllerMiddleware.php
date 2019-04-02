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

namespace Octopy\HTTP\Middleware;

class ControllerMiddleware
{
    /**
     * @var array
     */
    protected $option;

    /**
     * @param  array $option
     * @return void
     */
    public function __construct(array &$option)
    {
        $this->option = &$option;
    }

    /**
     * @param  mixed $method
     * @return $this
     */
    public function only($method)
    {
        $this->option['only'] = is_array($method) ? $method : func_get_args();

        return $this;
    }

    /**
     * @param  mixed $method
     * @return $this
     */
    public function except($method)
    {
        $this->option['except'] = is_array($method) ? $method : func_get_args();

        return $this;
    }
}
